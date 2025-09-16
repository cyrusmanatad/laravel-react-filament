<?php

namespace App\Http\Controllers;

use App\Jobs\OracleReports\SyncBranchPlantReportJob;
use App\Jobs\OracleReports\SyncBrandManagerReportJob;
use App\Jobs\OracleReports\SyncCustomerPersonnelReportJob;
use App\Jobs\OracleReports\SyncCustomerReportJob;
use App\Jobs\OracleReports\SyncDivisionReportJob;
use App\Jobs\OracleReports\SyncPersonnelReportJob;
use App\Jobs\OracleReports\SyncSkuReportJob;
use App\Jobs\OracleReports\SyncUtsSkuReportJob;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Dispatch a job to fetch a customer master report from Oracle BI.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fetchBrandManagerMaster()
    {
        try {
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_BRAND_MANAGER_RPT.xdo';
            
            $parameters = [
                'P_START_DATE' => '01-06-2020',
                'P_END_DATE' => '09-09-2025',
            ];

            // Dispatch the job to the queue
            SyncBrandManagerReportJob::dispatch($reportPath, $parameters);

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Successfully dispatched job to sync customer master report.');

        } catch (\Exception $e) {
            // Handle potential errors during dispatching (e.g., queue connection issues)
            return redirect()->back()->with('error', 'Failed to dispatch sync job: ' . $e->getMessage());
        }
    }
    /**
     * Dispatch a job to fetch a customer master report from Oracle BI.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fetchCustomerMaster()
    {
        try {
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_CUST_MASTER_RPT V2G.xdo';
            
            $parameters = [
                'P_START_DATE' => '01-06-2020',
                'P_END_DATE' => '09-09-2025',
            ];

            // Dispatch the job to the queue
            SyncCustomerReportJob::dispatch($reportPath, $parameters);

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Successfully dispatched job to sync customer master report.');

        } catch (\Exception $e) {
            // Handle potential errors during dispatching (e.g., queue connection issues)
            return redirect()->back()->with('error', 'Failed to dispatch sync job: ' . $e->getMessage());
        }
    }

    /**
     * Dispatch a job to fetch a customer master report from Oracle BI.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fetchPersonnelReport()
    {
        try {
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_PER_MASTER_RPT.xdo';
            
            $parameters = [
                'P_START_DATE' => '01-06-2020',
                'P_END_DATE' => '09-09-2025',
            ];

            // Dispatch the job to the queue
            SyncPersonnelReportJob::dispatch($reportPath, $parameters);

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Successfully dispatched job to sync customer master report.');

        } catch (\Exception $e) {
            // Handle potential errors during dispatching (e.g., queue connection issues)
            return redirect()->back()->with('error', 'Failed to dispatch sync job: ' . $e->getMessage());
        }
    }
    
    /**
     * Dispatch a job to fetch a customer master report from Oracle BI.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fetchDivisionReport()
    {
        try {
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_DIVISION_MASTER_RPT.xdo';
            
            $parameters = [
                'P_START_DATE' => '01-06-2020',
                'P_END_DATE' => '09-09-2025',
            ];

            // Dispatch the job to the queue
            SyncDivisionReportJob::dispatch($reportPath, $parameters);

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Successfully dispatched job to sync customer master report.');

        } catch (\Exception $e) {
            // Handle potential errors during dispatching (e.g., queue connection issues)
            return redirect()->back()->with('error', 'Failed to dispatch sync job: ' . $e->getMessage());
        }
    }

    /**
     * Dispatch a job to fetch a customer master report from Oracle BI.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fetchCustomerPersonnelReport()
    {
        try {
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_CUST_PERS_MASTER_RPT v1.xdo';
            
            $parameters = [
                'P_START_DATE' => '01-06-2020',
                'P_END_DATE' => '09-09-2025',
            ];

            // Dispatch the job to the queue
            SyncCustomerPersonnelReportJob::dispatch($reportPath, $parameters);

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Successfully dispatched job to sync customer master report.');

        } catch (\Exception $e) {
            // Handle potential errors during dispatching (e.g., queue connection issues)
            return redirect()->back()->with('error', 'Failed to dispatch sync job: ' . $e->getMessage());
        }
    }

    /**
     * Dispatch a job to fetch a customer master report from Oracle BI.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fetchBranchPlantReport()
    {
        try {
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_PLANT_MASTER_RPT.xdo';
            
            $parameters = [
                'P_START_DATE' => '01-06-2020',
                'P_END_DATE' => '09-09-2025',
            ];

            // Dispatch the job to the queue
            SyncBranchPlantReportJob::dispatch($reportPath, $parameters);

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Successfully dispatched job to sync customer master report.');

        } catch (\Exception $e) {
            // Handle potential errors during dispatching (e.g., queue connection issues)
            return redirect()->back()->with('error', 'Failed to dispatch sync job: ' . $e->getMessage());
        }
    }

    /**
     * Dispatch a job to fetch a customer master report from Oracle BI.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fetchSkuReport()
    {
        try {
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_MTL_MASTER_RPT_V4G.xdo';
            
            $parameters = [
                'P_START_DATE' => '01-06-2020',
                'P_END_DATE' => '09-09-2025',
            ];

            // Dispatch the job to the queue
            SyncSkuReportJob::dispatch($reportPath, $parameters);

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Successfully dispatched job to sync customer master report.');

        } catch (\Exception $e) {
            // Handle potential errors during dispatching (e.g., queue connection issues)
            return redirect()->back()->with('error', 'Failed to dispatch sync job: ' . $e->getMessage());
        }
    }

    /**
     * Dispatch a job to fetch a customer master report from Oracle BI.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function fetchUtsSkuReport()
    {
        try {
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_UTS_SKU_MASTER_RPT_V4G.xdo';
            
            $parameters = [
                'P_START_DATE' => '01-06-2020',
                'P_END_DATE' => '09-09-2025',
            ];

            // Dispatch the job to the queue
            SyncUtsSkuReportJob::dispatch($reportPath, $parameters);

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Successfully dispatched job to sync customer master report.');

        } catch (\Exception $e) {
            // Handle potential errors during dispatching (e.g., queue connection issues)
            return redirect()->back()->with('error', 'Failed to dispatch sync job: ' . $e->getMessage());
        }
    }

    public function fetchAllReport()
    {
        try {
            $parameters = [
                'P_START_DATE' => '01-06-2020',
                'P_END_DATE' => '09-09-2025',
            ];
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_BRAND_MANAGER_RPT.xdo';
            SyncBrandManagerReportJob::dispatch($reportPath, $parameters);
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_CUST_MASTER_RPT V2G.xdo';
            SyncCustomerReportJob::dispatch($reportPath, $parameters);
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_PER_MASTER_RPT.xdo';
            SyncPersonnelReportJob::dispatch($reportPath, $parameters);
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_DIVISION_MASTER_RPT.xdo';
            SyncDivisionReportJob::dispatch($reportPath, $parameters);
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_CUST_PERS_MASTER_RPT v1.xdo';
            SyncCustomerPersonnelReportJob::dispatch($reportPath, $parameters);
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_PLANT_MASTER_RPT.xdo';
            SyncBranchPlantReportJob::dispatch($reportPath, $parameters);
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_MTL_MASTER_RPT_V4G.xdo';
            SyncSkuReportJob::dispatch($reportPath, $parameters);
            $reportPath = '/Custom/PaaSIntegrationReports/Report/XXUN_UTS_SKU_MASTER_RPT_V4G.xdo';
            SyncUtsSkuReportJob::dispatch($reportPath, $parameters);
            return "Success";
        } catch (\Throwable $th) {
            return "Something went wrong!";
        }

    }
}
