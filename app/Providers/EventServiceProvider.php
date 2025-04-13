<?php

namespace App\Providers;

use App\Listeners\CreateAccountAfterRegistration;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{

    protected $listen = [
        Registered::class => [
            CreateAccountAfterRegistration::class,
        ],
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
