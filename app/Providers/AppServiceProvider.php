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

        // Share branches list with the main layout for the admin branch switcher
        View::composer('layouts.app', function ($view) {
            if (auth()->check() && auth()->user()->role === 'admin') {
                $view->with('branches', \App\Models\Branch::where('active', true)->orderBy('name')->get());
            } else {
                $view->with('branches', collect());
            }
        });
    }
}
