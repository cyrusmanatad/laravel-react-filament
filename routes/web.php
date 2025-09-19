<?php

use App\Http\Controllers\API\OrderEntryDetailsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('dashboard');
    })->name('dashboard');
    Route::get('orders/create', function () {
        return Inertia::render('create-order');
    })->name('create-order');

    // Order Entry Routes
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';


// Report Routes
Route::name('reports.')->group(function () {
    Route::get('/reports/brand-manager-master', [ReportController::class, 'fetchBrandManagerMaster'])->name('brand-manager');
    Route::get('/reports/customer-master', [ReportController::class, 'fetchCustomerMaster'])->name('customer');
    Route::get('/reports/personnel-master', [ReportController::class, 'fetchPersonnelReport'])->name('personnel');
    Route::get('/reports/division-master', [ReportController::class, 'fetchDivisionReport'])->name('division');
    Route::get('/reports/customer-personnel-master', [ReportController::class, 'fetchCustomerPersonnelReport'])->name('customer-personnel');
    Route::get('/reports/branch-plant-master', [ReportController::class, 'fetchBranchPlantReport'])->name('branch-plant');
    Route::get('/reports/sku-master', [ReportController::class, 'fetchSkuReport'])->name('sku');
    Route::get('/reports/uts-sku-master', [ReportController::class, 'fetchUtsSkuReport'])->name('uts-sku');
    Route::get('/reports/all', [ReportController::class, 'fetchAllReport'])->name('all-reports');
});

Route::get('/order-type', [OrderEntryDetailsController::class, 'getOrderTypes'])->name('api.order.types');
Route::post('/divisions', [OrderEntryDetailsController::class, 'getDivisions'])->name('api.divisions');
Route::get('/personnels', [OrderEntryDetailsController::class, 'getPersonnels'])->name('api.personnels');
Route::get('/plants', [OrderEntryDetailsController::class, 'getPlants'])->name('api.plants');
Route::post('/customers', [OrderEntryDetailsController::class, 'getCustomers'])->name('api.customers');
Route::post('/skus', [OrderEntryDetailsController::class, 'getSkus'])->name('api.skus');