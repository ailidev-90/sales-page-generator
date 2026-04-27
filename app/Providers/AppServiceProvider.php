<?php

namespace App\Providers;

use App\Models\SalesPage;
use App\Policies\SalesPagePolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(SalesPage::class, SalesPagePolicy::class);
    }
}
