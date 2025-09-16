<?php

namespace App\Jobs\OracleReports;

use App\Models\BranchPlant;
use App\Models\BrandManager;
use App\Models\Division;
use App\Services\OracleBIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncBrandManagerReportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The path to the report on the Oracle BI server.
     *
     * @var string
     */
    protected $reportPath;

    /**
     * The parameters for the report.
     *
     * @var array
     */
    protected $parameters;

    /**
     * Create a new job instance.
     */
    public function __construct(string $reportPath, array $parameters)
    {
        $this->reportPath = $reportPath;
        $this->parameters = $parameters;
    }

    /**
     * Execute the job.
     */
    public function handle(OracleBIService $oracleBiService): void
    {
        try {
            Log::info("Starting Oracle BI report sync for: {" . $this->reportPath . "} ");

            $csvData = $oracleBiService->runReport($this->reportPath, $this->parameters);

            // A more reliable way to parse CSV data
            $csvLines = explode("\n", trim($csvData));
            if (count($csvLines) < 2) {
                Log::info('No data rows found in CSV report.');
                return;
            }

            $headerLine = array_shift($csvLines);
            // Clean the BOM (Byte Order Mark) from the header line
            $headerLine = ltrim($headerLine, "\xEF\xBB\xBF"); 
            $headers = str_getcsv($headerLine);

            Log::info("CSV Headers found: " . implode(', ', $headers));

            $brandToUpsert = [];
            $batch = 500;
            foreach ($csvLines as $line) {
                if (empty(trim($line))) {
                    continue; // Skip empty lines
                }

                $row = str_getcsv($line);
                if (count($row) !== count($headers)) {
                    Log::warning('Skipping mismatched row: ' . $line);
                    continue;
                }

                $data = array_combine($headers, $row);

                if(empty($data['BRAND_MANAGER_ID']) || empty($data['BRAND_SUPER_MGR_ID']) || empty($data['PARTY_NUMBER']))
                {
                    Log::warning('Skipping row with missing required fields: ' . $line);
                    continue;
                }

                // Prepare array for batch upsert
                $brandToUpsert[] = [
                    'site_user_id' => $data['SITE_USE_ID'],
                    'cust_code' => $data['PARTY_NUMBER'],
                    'bm_email' => $data['BRAND_MGR_EMAIL'] ?? '',
                    'bm_code' => $data['BRAND_SUPER_MGR_ID'],
                    'bm_name' => $data['BRAND_SUPER_MGR'],
                    'bp_code' => $data['BRAND_MANAGER_ID'],
                    'bp_name' => $data['BRAND_MANAGER_NAME']
                ];

                if(count($brandToUpsert) === $batch)
                {
                    BrandManager::upsert(
                        $brandToUpsert,
                        ['site_user_id', 'cust_code', 'bp_code'], // The column to check for uniqueness
                        ['bm_email', 'bm_code', 'bm_name', 'bp_name', 'updated_at'] // The columns to update if a record exists
                    );

                    Log::info("Successfully synced {" . count($brandToUpsert) . "} records from {" . $this->reportPath . "}");
                    $brandToUpsert = [];
                }
            }

            if (!empty($brandToUpsert)) {
                // Perform a single, efficient batch operation.
                BrandManager::upsert(
                    $brandToUpsert,
                    ['site_user_id', 'cust_code', 'bp_code'], // The column to check for uniqueness
                    ['bm_email', 'bm_code', 'bm_name', 'bp_name', 'updated_at'] // The columns to update if a record exists
                );
            }

            Log::info("Successfully synced {" . count($brandToUpsert) . "} records from {" . $this->reportPath . "}");

        } catch (\Exception $e) {
            Log::error("Failed to sync Oracle BI report: " . $e->getMessage());
            $this->fail($e);
        }
    }
}
