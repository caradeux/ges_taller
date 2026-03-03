<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\InsuranceCompanyController;
use App\Http\Controllers\LiquidatorController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceItemController;
use App\Http\Controllers\VehicleBrandController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\BranchSwitchController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('clients', ClientController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('quotations', QuotationController::class);
    Route::get('quotations/{quotation}/pdf', [QuotationController::class, 'downloadPDF'])->name('quotations.pdf');
    Route::post('quotations/{quotation}/status', [QuotationController::class, 'updateStatus'])->name('quotations.status');
    Route::resource('insurance-companies', InsuranceCompanyController::class);
    Route::resource('liquidators', LiquidatorController::class);
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('reportes', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reportes/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');

    // Branches management (admin only)
    Route::resource('branches', BranchController::class)->except(['show', 'create', 'edit']);
    Route::post('branch-switch', [BranchSwitchController::class, 'switch'])->name('branch.switch');

    // Users management
    Route::get('users/permissions', [UserController::class, 'permissions'])->name('users.permissions');
    Route::resource('users', UserController::class)->except(['show']);
    Route::post('users/{user}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');

    // Service items catalog
    Route::resource('service-items', ServiceItemController::class)->except(['show']);
    Route::get('api/service-items/search', [ServiceItemController::class, 'search'])->name('service-items.search');

    // Vehicle brands & models
    Route::resource('vehicle-brands', VehicleBrandController::class)->except(['show', 'create', 'edit']);
    Route::post('vehicle-brands/{vehicleBrand}/models', [VehicleBrandController::class, 'storeModel'])->name('vehicle-brands.models.store');
    Route::delete('vehicle-brands/{vehicleBrand}/models/{vehicleModel}', [VehicleBrandController::class, 'destroyModel'])->name('vehicle-brands.models.destroy');
    Route::get('api/vehicle-brands/{vehicleBrand}/models', [VehicleBrandController::class, 'modelsByBrand'])->name('vehicle-brands.models.json');
    Route::get('api/vehicle-brands', [VehicleBrandController::class, 'brandsJson'])->name('vehicle-brands.json');
});
