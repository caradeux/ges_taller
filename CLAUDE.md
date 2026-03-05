# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Ges_Taller** is a Laravel 12 auto repair shop management system localized for Chile (Spanish UI, CLP currency, Chilean RUT identifiers). It manages clients, vehicles, quotations, insurance companies, liquidators, and supports multiple branches with role-based access.

## Commands

### Setup
```bash
composer run setup   # Install deps, copy .env, generate key, migrate, npm install & build
```

### Development
```bash
composer run dev     # Starts concurrently: Laravel server, queue worker, Pail log viewer, Vite
```

### Testing
```bash
composer run test                              # Clears config cache then runs PHPUnit
php artisan test --filter TestClassName        # Single test class
php artisan test --filter test_method_name     # Single test method
```

### Database
```bash
php artisan migrate
php artisan db:seed              # Seeds admin (admin@gestaller.cl / admin123) + sample data
php artisan migrate:fresh --seed
```

> **Windows/Laragon:** `php` is not on the bash PATH. Use the full path:
> `/c/laragon/bin/php/php-8.3.30-Win32-vs16-x64/php.exe artisan <command>`

### Code Quality
```bash
./vendor/bin/pint    # Laravel Pint (PSR-12 formatter)
```

## Architecture

### Database (MySQL — XAMPP, root, no password, db: `ges_taller`)

Core entity graph:
- **Client** → has many Vehicles, Quotations
- **Vehicle** → belongs to Client, has many Quotations
- **Quotation** → belongs to Client, Vehicle, InsuranceCompany (nullable), Liquidator (nullable); has many QuotationItems
- **QuotationItem** → belongs to Quotation and UnType; fields: `un_type_id`, `description`, `price`, `is_salvage`
- **UnType** — item classification catalog; categories: `repair`, `paint`, `dm`, `parts`, `other`
- **InsuranceCompany** → has many Liquidators
- **Branch** — multi-branch support; `branch_id` FK on users, clients, vehicles, quotations
- **Company** — single-record config table (name, rut, address, `quotation_validity_days`, `folio_counter`)

> Use `DATE_FORMAT(date, '%Y-%m')`, `MONTH(date)`, `YEAR(date)` for date grouping — NOT SQLite's `strftime()`.

### Quotation Lifecycle
- **Folio**: assigned only when status transitions to `sent` (drafts have no folio).
  - Atomic assignment via `DB::transaction` + `Company::lockForUpdate()` (prevents race conditions with concurrent secretaries).
  - Format: `str_pad($company->folio_counter, 4, '0', STR_PAD_LEFT)`, then `folio_counter` is incremented.
  - `$quotation->folio_display` accessor returns folio string or `"Borrador"`.
  - PDF is blocked (`downloadPDF()`) if folio is null.
- Status enum: `draft → sent → approved → rejected → finished → invoiced`
- Status transitions via `QuotationController@updateStatus` (`POST /quotations/{id}/status`)
- Status display via model accessors: `getStatusLabelAttribute()`, `getStatusColorAttribute()`
- `Company::current()` — always returns the single company record via `firstOrCreate`

### Multi-Branch Architecture
- `User::activeBranchId()` — admin uses `session('active_branch_id')` (null = all branches); other roles always see their own `branch_id`
- All data-listing controllers filter with `->when($branchId, fn($q) => $q->where('branch_id', $branchId))`
- Admin can switch active branch via `POST /branch-switch` (sidebar dropdown)
- `AppServiceProvider` shares `$branches` with `layouts.app` via a ViewComposer (active branches, admin-only)

### Roles & Permissions
Three roles: `admin`, `recepcion`, `taller`.

- `admin`: full access, can manage users/branches/roles/catalogs
- `recepcion`: quotations (no delete), clients/vehicles (no delete), liquidators, insurance companies, reports
- `taller`: read-only on quotations, clients, vehicles

`CheckRolePermission` middleware auto-applied to all web routes. Rules live in `config/permissions.php` as route-name patterns (`quotations.*`, exact names). Admins bypass all permission checks. No matching pattern = allow (auth middleware already ensures login).

To add a new route permission: add an entry to `config/permissions.php`.

### Frontend Stack
- **Bootstrap 5.3**, **Bootstrap Icons**, **Chart.js**, **Google Fonts** — all served locally from `public/vendor/` (no CDN)
- Blade templates with shared layout at `resources/views/layouts/app.blade.php`
- Layout sections: `@yield('content')`, `@yield('styles')`, `@yield('scripts')` — use `@section`, NOT `@push`
- PDF generation: `barryvdh/laravel-dompdf` → `resources/views/quotations/pdf.blade.php`
- Chart.js: wrap canvas in `<div style="position: relative; height: Xpx;">` to prevent infinite resize loop

### Authentication
Custom auth via `App\Http\Controllers\Auth\LoginController`. All routes except `login`/`logout` require the `auth` middleware. Inactive users (`active = false`) are auto-logged out by `CheckRolePermission`.

### API Endpoints (internal JSON)
- `GET /api/clients/search?q=` → client autocomplete (name/RUT, max 15, branch-scoped)
- `GET /api/vehicles/search?q=&client_id=` → vehicle autocomplete (plate/brand/model, branch-scoped)
- `GET /api/un-types` → UnType list for quotation item dropdowns
- `GET /api/service-items/search?q=` → service item autocomplete
- `GET /api/vehicle-brands` → brand list
- `GET /api/vehicle-brands/{id}/models` → models for a brand

### Autocomplete pattern
Forms that select clients or vehicles use a text input (`id="X_text"`) + hidden input (`name="X_id"`) pattern, NOT a `<select>`. The `initAC()` JS function (defined inline in each view) drives the dropdown. Used in: `quotations/create`, `quotations/edit`, `vehicles/edit`. Never load full client/vehicle lists at page render.

### Catalogs (admin-only management)
- **UnType** (`/un-types`) — item type tags for quotation lines (repair/paint/dm/parts/other)
- **ServiceItem** (`/service-items`) — reusable item catalog with default prices
- **VehicleBrand** (`/vehicle-brands`) — brands and nested models

### Localization
All UI is in Spanish. When adding status labels, currency, or dates: CLP for currency, `d/m/Y` date format, Spanish labels in model accessors.
