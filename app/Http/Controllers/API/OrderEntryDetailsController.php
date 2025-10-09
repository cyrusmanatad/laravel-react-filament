<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BranchPlant;
use App\Models\Customer;
use App\Models\CustomerPersonnel;
use App\Models\Division;
use App\Models\OrderType;
use App\Models\Personnel;
use App\Models\UtsSku;
use Illuminate\Http\Request;

class OrderEntryDetailsController extends Controller
{
    public function getOrderTypes()
    {
        $data = OrderType::all()->map(fn($row) => [
            'value' => $row->ot_code,
            'label' => $row->title,
        ]);
        return response()->json($data);
    }

    public function getDivisions(Request $request)
    {
        $personnelCode = $request->input('personnel_code');

        if (!$personnelCode) {
            return response()->json([]);
        }

        // 1. Find distinct division codes from customer_personnels for the given personnel
        $divisionCodes = CustomerPersonnel::where('psr_code', $personnelCode)
                                    ->distinct()
                                    ->pluck('div_code');

        // 2. Fetch the divisions based on the collected division codes
        $divisions = Division::whereIn('div_code', $divisionCodes)
                            ->get()
                            ->map(function ($division) {
                                return [
                                    'value' => $division->div_code,
                                    'label' => $division->div_desc,
                                ];
                            });

        return response()->json($divisions);
    }

    public function getCustomers(Request $request)
    {
        $personnelCode = $request->input('personnel_code');
        $divisionCode = $request->input('division_code');

        if (!$personnelCode) {
            return response()->json([]);
        }

        // 1. Find distinct customer codes from customer_personnels for the given personnel
        $custCodes = CustomerPersonnel::where([
                                        'psr_code' => $personnelCode,
                                        'div_code' => $divisionCode,
                                    ])
                                    ->distinct()
                                    ->pluck('cust_code');

        // 2. Fetch the customer based on the collected cutomer codes
        $customers = Customer::whereIn('cust_code', $custCodes)
                            ->get()
                            ->map(function ($row) {
                                return [
                                    'value' => $row->cust_code,
                                    'label' => $row->name,
                                ];
                            });

        return response()->json($customers);
    }

    public function getPlants()
    {
        $data = BranchPlant::all()->map(fn($row) => [
            'value' => $row->branch_code,
            'label' => $row->branch_desc,
        ]);
        return response()->json($data);
    }

    public function getPersonnels()
    {
        $data = Personnel::all()->map(fn ($row) => [
            'value' => $row->psr_code,
            'label' => $row->name,
        ]);
        return response()->json($data);
    }

    public function getSkus(Request $request)
    {
        $divisionCode = $request->input('division_code');
        $customerCode = $request->input('customer_code');
        $branchPlant = $request->input('branch_plant');

        $baseQuery = UtsSku::query()
            ->join('skus', 'uts_skus.sku_code', '=', 'skus.sku_code')
            ->where('skus.tagging', '!=', 'NON-TRADE');

        $data = (clone $baseQuery)->where([
            'uts_skus.cust_site' => $customerCode,
            'uts_skus.div_code' => $divisionCode,
            'uts_skus.branch_code' => $branchPlant
        ])->groupBy('uts_skus.sku_code')
        ->get();

        if(count($data)) return $this->mapSkus($data);

        $data = (clone $baseQuery)->where([
            'uts_skus.div_code' => $divisionCode,
            'uts_skus.branch_code' => $branchPlant
        ])->groupBy('uts_skus.sku_code')
        ->get();

        if(count($data)) return $this->mapSkus($data);

        $data = (clone $baseQuery)->where([
            'uts_skus.div_code' => $divisionCode
        ])->groupBy('uts_skus.sku_code')
        ->get();

        if(count($data)) return $this->mapSkus($data);

        return response()->json([]);
    }

    private function mapSkus($data)
    {
        $data = $data->map(fn ($row) => [
            'value' => $row->sku_code,
            'label' => $row->sku_code ." - ". $row->sku_desc,
            'uom' => $row->sku_uom,
            'unit_price' => !!$row->matrix_price ? $row->matrix_price : $row->sku_price,
        ]);

        return response()->json($data);
    }
}
