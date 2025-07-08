<?php

namespace App\Providers;


use App\Repositories\Eloquent\AvailabilityRepository;
use App\Repositories\Eloquent\BookingRepository;
use App\Repositories\Eloquent\PropertyRepository;
use App\Repositories\Interfaces\AvailabilityRepositoryInterface;
use App\Repositories\Interfaces\BookingRepositoryInterface;
use App\Repositories\Interfaces\PropertyRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            PropertyRepositoryInterface::class,
            PropertyRepository::class
        );

        $this->app->singleton(
            AvailabilityRepositoryInterface::class,
            AvailabilityRepository::class
        );

        $this->app->singleton(
            BookingRepositoryInterface::class,
            BookingRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
