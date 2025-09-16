<?php

namespace App\Jobs\OracleReports;

use App\Models\Customer;
use App\Models\CustomerPersonnel;
use App\Services\OracleBIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncCustomerPersonnelReportJob implements ShouldQueue
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

            $csvLines = explode("\n", trim($csvData));
            if (count($csvLines) < 2) {
                Log::info('No data rows found in CSV report.');
                return;
            }

            $headerLine = array_shift($csvLines);
            $headerLine = ltrim($headerLine, "\xEF\xBB\xBF"); 
            $headers = str_getcsv($headerLine);

            Log::info("CSV Headers found: " . implode(', ', $headers));

            $batch = 500;
            foreach ($csvLines as $line) {
                if (empty(trim($line))) {
                    continue; 
                }

                $row = str_getcsv($line);
                if (count($row) !== count($headers)) {
                    Log::warning('Skipping mismatched row: ' . $line);
                    continue;
                }

                $data = array_combine($headers, $row);

                if (empty($data['PARTY_NUMBER']) || empty($data['PROD_DIV_CODE']) || empty($data['SALES_REP_ID'])) {
                    Log::warning('Skipping row with missing required fields: ' . $line);
                    continue;
                }

                // Prepare array for batch upsert
                $custPsrToUpsert[] = [
                    'cust_code' => $data['PARTY_NUMBER'],
                    'div_code' => $data['PROD_DIV_CODE'],
                    'psr_code' => $data['SALES_REP_ID'],
                    'emp_id' => $data['PERSON_NUMBER'] ?? null,
                    'bp_code' => $data['BRAND_MANAGER_ID'] ?? null,
                    'bm_code' => $data['BRAND_SUPER_MGR_ID'] ?? null
                ];

                if(count($custPsrToUpsert) === $batch)
                {
                    CustomerPersonnel::upsert(
                        $custPsrToUpsert,
                        ['cust_code', 'div_code', 'psr_code'], // The column to check for uniqueness
                        ['emp_id', 'bp_code', 'bm_code', 'created_at'] // The columns to update if a record exists
                    );

                    Log::info("Successfully synced {" . count($custPsrToUpsert) . "} records from {" . $this->reportPath . "}");
                    $custPsrToUpsert = [];
                }

                // CustomerPersonnel::updateOrInsert(
                //     [
                //         'cust_code' => $data['PARTY_NUMBER'],
                //         'div_code' => $data['PROD_DIV_CODE'],
                //         'psr_code' => $data['SALES_REP_ID'],
                //     ],
                //     [
                //         'emp_id' => $data['PERSON_NUMBER'] ?? null,
                //         'bp_code' => $data['BRAND_MANAGER_ID'] ?? null,
                //         'bm_code' => $data['BRAND_SUPER_MGR_ID'] ?? null,
                //         'created_at' => now(),
                //     ]
                // );
            }

            if (!empty($custPsrToUpsert)) {
                // Perform a single, efficient batch operation.
                CustomerPersonnel::upsert(
                    $custPsrToUpsert,
                    ['cust_code', 'div_code', 'psr_code'], // The column to check for uniqueness
                    ['emp_id', 'bp_code', 'bm_code', 'created_at'] // The columns to update if a record exists
                );

                Log::info("Successfully synced {" . count($custPsrToUpsert) . "} records from {" . $this->reportPath . "}");
            }

        } catch (\Exception $e) {
            Log::error("Failed to sync Oracle BI report: " . $e->getMessage());
            $this->fail($e);
        }
    }
}
