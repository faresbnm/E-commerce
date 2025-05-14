<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Register your model policies here if needed
    ];

    public function boot()
    {
        $this->registerPolicies();

        // Define your authorization gates
        Gate::define('manage-products', function ($user) {
            return $user->isItCommercial();
        });

        Gate::define('approve-content', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('manage-users', function ($user) {
            return $user->isAdmin();
        });
    }
}