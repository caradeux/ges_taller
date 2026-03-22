<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\WorkOrderController;
use App\Http\Controllers\PartOrderController;
use App\Http\Controllers\TagController;
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
use App\Http\Controllers\PartController;
use Illuminate\Support\Facades\Route;

// Auth Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Clients
    Route::post('clients/quick', [ClientController::class, 'quickStore'])->name('clients.quickStore');
    Route::get('api/clients/search', [ClientController::class, 'search'])->name('clients.search');
    Route::resource('clients', ClientController::class);

    // Vehicles
    Route::post('vehicles/quick', [VehicleController::class, 'quickStore'])->name('vehicles.quickStore');
    Route::get('api/vehicles/search', [VehicleController::class, 'search'])->name('vehicles.search');
    Route::resource('vehicles', VehicleController::class);

    // Work Orders (OT)
    Route::get('work-orders/seguimiento', [WorkOrderController::class, 'followUp'])->name('work-orders.followup');
    Route::resource('work-orders', WorkOrderController::class);
    Route::get('work-orders/{work_order}/pdf', [WorkOrderController::class, 'downloadPDF'])->name('work-orders.pdf');
    Route::get('work-orders/{work_order}/invoice-pdf', [WorkOrderController::class, 'downloadInvoicePDF'])->name('work-orders.invoice-pdf');
    Route::get('work-orders/{work_order}/intake-pdf', [WorkOrderController::class, 'downloadIntakePDF'])->name('work-orders.intake-pdf');
    Route::post('work-orders/{work_order}/status', [WorkOrderController::class, 'updateStatus'])->name('work-orders.status');
    Route::post('work-orders/{work_order}/items/{item}/toggle-approval', [WorkOrderController::class, 'toggleItemApproval'])->name('work-orders.toggle-approval');

    // Part Orders (Repuestos)
    Route::post('work-orders/{work_order}/parts', [PartOrderController::class, 'store'])->name('part-orders.store');
    Route::put('part-orders/{partOrder}', [PartOrderController::class, 'update'])->name('part-orders.update');
    Route::post('part-orders/{partOrder}/received', [PartOrderController::class, 'markReceived'])->name('part-orders.received');
    Route::delete('part-orders/{partOrder}', [PartOrderController::class, 'destroy'])->name('part-orders.destroy');

    // Tags
    Route::resource('tags', TagController::class)->except(['show', 'create', 'edit']);
    Route::get('api/tags/search', [TagController::class, 'search'])->name('tags.search');

    // Insurance Companies
    Route::post('insurance-companies/quick', [InsuranceCompanyController::class, 'quickStore'])->name('insurance-companies.quickStore');
    Route::resource('insurance-companies', InsuranceCompanyController::class)->except(['create', 'show', 'edit']);

    // Liquidators
    Route::post('liquidators/quick', [LiquidatorController::class, 'quickStore'])->name('liquidators.quickStore');
    Route::resource('liquidators', LiquidatorController::class)->except(['create', 'show', 'edit']);

    // Profile
    Route::get('profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Reports
    Route::get('reportes', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reportes/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
    Route::get('reportes/aseguradoras', [ReportController::class, 'insuranceReport'])->name('reports.insurance');
    Route::get('reportes/rentabilidad', [ReportController::class, 'profitabilityReport'])->name('reports.profitability');
    Route::get('reportes/facturacion', [ReportController::class, 'billingReport'])->name('reports.billing');
    Route::get('reportes/repuestos', [ReportController::class, 'partsReport'])->name('reports.parts');

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
    Route::post('service-items/types', [ServiceItemController::class, 'storeType'])->name('service-items.store-type');
    Route::get('api/service-items/search', [ServiceItemController::class, 'search'])->name('service-items.search');

    // Parts catalog (partes y piezas)
    Route::resource('parts', PartController::class)->except(['show', 'create', 'edit']);
    Route::post('parts/quick', [PartController::class, 'quickStore'])->name('parts.quickStore');
    Route::get('api/parts/search', [PartController::class, 'search'])->name('parts.search');

    // SLA / Control de Tiempos
    Route::get('sla', [\App\Http\Controllers\SlaController::class, 'index'])->name('sla.index');
    Route::post('sla', [\App\Http\Controllers\SlaController::class, 'updateSla'])->name('sla.update');

    // Holidays (admin)
    Route::get('holidays', [\App\Http\Controllers\HolidayController::class, 'index'])->name('holidays.index');
    Route::post('holidays', [\App\Http\Controllers\HolidayController::class, 'store'])->name('holidays.store');
    Route::delete('holidays/{holiday}', [\App\Http\Controllers\HolidayController::class, 'destroy'])->name('holidays.destroy');
    Route::post('holidays/seed', [\App\Http\Controllers\HolidayController::class, 'seedYear'])->name('holidays.seed');

    // Vehicle brands & models
    Route::resource('vehicle-brands', VehicleBrandController::class)->except(['show', 'create', 'edit']);
    Route::post('vehicle-brands/{vehicleBrand}/models', [VehicleBrandController::class, 'storeModel'])->name('vehicle-brands.models.store');
    Route::delete('vehicle-brands/{vehicleBrand}/models/{vehicleModel}', [VehicleBrandController::class, 'destroyModel'])->name('vehicle-brands.models.destroy');
    Route::get('api/vehicle-brands/{vehicleBrand}/models', [VehicleBrandController::class, 'modelsByBrand'])->name('vehicle-brands.models.json');
    Route::get('api/vehicle-brands', [VehicleBrandController::class, 'brandsJson'])->name('vehicle-brands.json');
});
