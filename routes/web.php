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
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UnTypeController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('clients/quick', [ClientController::class, 'quickStore'])->name('clients.quickStore');
    Route::get('api/clients/search', [ClientController::class, 'search'])->name('clients.search');
    Route::resource('clients', ClientController::class);
    Route::post('vehicles/quick', [VehicleController::class, 'quickStore'])->name('vehicles.quickStore');
    Route::get('api/vehicles/search', [VehicleController::class, 'search'])->name('vehicles.search');
    Route::resource('vehicles', VehicleController::class);
    Route::get('quotations/seguimiento', [QuotationController::class, 'followUp'])->name('quotations.followup');
    Route::resource('quotations', QuotationController::class);
    Route::get('quotations/{quotation}/pdf', [QuotationController::class, 'downloadPDF'])->name('quotations.pdf');
    Route::post('quotations/{quotation}/status', [QuotationController::class, 'updateStatus'])->name('quotations.status');
    Route::post('insurance-companies/quick', [InsuranceCompanyController::class, 'quickStore'])->name('insurance-companies.quickStore');
    Route::resource('insurance-companies', InsuranceCompanyController::class)->except(['create', 'show', 'edit']);
    Route::post('liquidators/quick', [LiquidatorController::class, 'quickStore'])->name('liquidators.quickStore');
    Route::resource('liquidators', LiquidatorController::class)->except(['create', 'show', 'edit']);
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('reportes', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reportes/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');

    // Branches management (admin only)
    Route::resource('branches', BranchController::class)->except(['show', 'create', 'edit']);
    Route::post('branch-switch', [BranchSwitchController::class, 'switch'])->name('branch.switch');

    // Roles management (admin only)
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

    // Users management
    Route::get('users/permissions', [UserController::class, 'permissions'])->name('users.permissions');
    Route::resource('users', UserController::class)->except(['show']);
    Route::post('users/{user}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');

    // UN Types catalog
    Route::resource('un-types', UnTypeController::class)->except(['show', 'create', 'edit']);
    Route::get('api/un-types', [UnTypeController::class, 'json'])->name('un-types.json');

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
