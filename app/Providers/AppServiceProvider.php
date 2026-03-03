<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
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
