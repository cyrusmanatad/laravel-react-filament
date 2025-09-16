<?php

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