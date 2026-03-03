# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

**Ges_Taller** is a Laravel 12 auto repair shop management system localized for Chile (Spanish UI, CLP currency, Chilean RUT/DNI identifiers). It manages clients, vehicles, quotations, insurance companies, and liquidators.

## Commands

### Setup
```bash
composer run setup   # Full setup: install deps, copy .env, generate key, migrate, npm install & build
```

### Development
```bash
composer run dev     # Starts all services concurrently: Laravel server, queue worker, Pail log viewer, and Vite
```

### Testing
```bash
composer run test    # Clears config cache then runs PHPUnit
php artisan test --filter TestClassName   # Run a single test class
php artisan test --filter test_method_name  # Run a single test method
```

### Database
```bash
php artisan migrate
php artisan db:seed           # Seeds admin user (admin@gestaller.cl / admin123) + sample data
php artisan migrate:fresh --seed  # Reset and reseed
```

> **Nota:** En este entorno (Laragon en Windows), `php` no está en el PATH de bash. Usar la ruta completa:
> `/c/laragon/bin/php/php-8.3.30-Win32-vs16-x64/php.exe artisan <comando>`

### Code Quality
```bash
./vendor/bin/pint             # Laravel Pint (PSR-12 code formatter)
```

### Assets
```bash
npm run build   # Production build via Vite
npm run dev     # Vite dev server (included in composer run dev)
```

## Architecture

### Database Schema
The core entity graph:
- **Client** → has many **Vehicles** and **Quotations**
- **Vehicle** → belongs to Client, has many Quotations
- **Quotation** → belongs to Client, Vehicle, InsuranceCompany (nullable), Liquidator (nullable); has many **QuotationItems**
- **InsuranceCompany** → has many **Liquidators**
- **Liquidator** → belongs to InsuranceCompany

The database is SQLite by default (file at `database/database.sqlite`).

### Quotation Lifecycle
Folios are auto-generated as `COT-{uniqid()}`. Status enum: `draft → sent → approved → rejected → finished → invoiced`. The `QuotationController@updateStatus` route handles transitions. Status display is handled by model accessors `getStatusLabelAttribute()` and `getStatusColorAttribute()`.

### QuotationItem Types
Items are classified as either `repuesto` (parts) or `mano_obra` (labor).

### Frontend Stack
- **Bootstrap 5.3** loaded via CDN (not compiled — Tailwind is installed as a dev dependency but Bootstrap is used for the actual UI)
- **Bootstrap Icons** via CDN
- **Google Fonts**: Inter (body) and Outfit (headings)
- Blade templates with a single shared layout at `resources/views/layouts/app.blade.php`
- Layout uses `@yield('content')`, `@yield('styles')`, and `@yield('scripts')` sections
- PDF generation via `barryvdh/laravel-dompdf` — see `quotations/pdf.blade.php`

### Authentication
Custom auth using `App\Http\Controllers\Auth\LoginController`. All routes except login/logout are protected by the `auth` middleware (defined in `routes/web.php`).

### Dashboard
`DashboardController` computes a 6-month revenue chart (invoiced quotations grouped by month) and summary stats (client count, vehicle count, pending/approved quotation counts, recent quotations). Data is passed to `resources/views/dashboard.blade.php`.

### Localization
The application is in Spanish throughout. When adding new status labels, currency formatting, or date displays, maintain Chilean conventions (CLP for currency, `d/m/Y` date format, Spanish status names in model accessors).
