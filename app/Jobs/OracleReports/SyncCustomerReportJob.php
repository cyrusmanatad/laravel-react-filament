<?php

namespace App\Jobs\OracleReports;

use App\Models\Customer;
use App\Services\OracleBIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SyncCustomerReportJob implements ShouldQueue
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

            $customersToUpsert = [];
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

                if(empty($data['CUSTOMER_CODE_ID']) || empty($data['CUSTOMER_NAME']))
                {
                    Log::warning('Skipping row with missing required fields: ' . $line);
                    continue;
                }

                // Prepare array for batch upsert
                $customersToUpsert[] = [
                    'cust_code' => $data['CUSTOMER_CODE_ID'],
                    'name' => $data['CUSTOMER_NAME'],
                    'ship_to_address' => $data['CUSTOMER_SHIP_TO'] ?? null,
                    'bill_to_address' => $data['CUSTOMER_BILL_TO'] ?? null,
                    'ship_to_site_name' => $data['CUSTOMER_SHIP_TO_SITE_NAME'] ?? null
                ];

                if(count($customersToUpsert) === $batch)
                {
                    Customer::upsert(
                        $customersToUpsert,
                        ['cust_code'], // The column to check for uniqueness
                        ['name', 'ship_to_address', 'bill_to_address', 'ship_to_site_name', 'updated_at'] // The columns to update if a record exists
                    );

                    Log::info("Successfully synced {" . count($customersToUpsert) . "} records from {" . $this->reportPath . "}");
                    $customersToUpsert = [];
                }
            }

            if (!empty($customersToUpsert)) {
                // Perform a single, efficient batch operation.
                Customer::upsert(
                    $customersToUpsert,
                    ['customer_code_id'], // The column to check for uniqueness
                    ['name', 'ship_to_address', 'bill_to_address', 'ship_to_site_name', 'updated_at'] // The columns to update if a record exists
                );
            }

            Log::info("Successfully synced {" . count($customersToUpsert) . "} records from {" . $this->reportPath . "}");

        } catch (\Exception $e) {
            Log::error("Failed to sync Oracle BI report: " . $e->getMessage());
            $this->fail($e);
        }
    }
}
