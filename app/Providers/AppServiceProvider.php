<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Set Carbon locale to Spanish (Chile)
        \Carbon\Carbon::setLocale('es');

        // Use Bootstrap 5 pagination globally
        Paginator::useBootstrapFive();

        // Share branches list with the main layout for the admin branch switcher (cached per request)
        View::composer('layouts.app', function ($view) {
            static $branches = null;
            if ($branches === null) {
                $branches = auth()->check() && auth()->user()->role === 'admin'
                    ? \App\Models\Branch::where('active', true)->orderBy('name')->get()
                    : collect();
            }
            $view->with('branches', $branches);
        });
    }
}
