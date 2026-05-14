<?php

namespace App\Providers;

use App\Services\Contracts\LocationServiceInterface;
use App\Services\Contracts\SensorServiceInterface;
use App\Services\Contracts\SummaryServiceInterface;
use App\Services\Contracts\VisitorServiceInterface;
use App\Services\LocationService;
use App\Services\SensorService;
use App\Services\SummaryService;
use App\Services\VisitorService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(LocationServiceInterface::class, LocationService::class);
        $this->app->bind(SensorServiceInterface::class, SensorService::class);
        $this->app->bind(VisitorServiceInterface::class, VisitorService::class);
        $this->app->bind(SummaryServiceInterface::class, SummaryService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
