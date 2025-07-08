<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AuditService;
use App\Services\NotificationService;
use App\Services\FundRequestService;
use App\Services\Interfaces\FundRequestServiceInterface;
use App\Repositories\FundRequestRepository;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(AuditService::class, function ($app) {
            return new AuditService();
        });

        $this->app->singleton(NotificationService::class, function ($app) {
            return new NotificationService();
        });

        $this->app->bind(FundRequestServiceInterface::class, function ($app) {
            return new FundRequestService(
                $app->make(FundRequestRepository::class),
                $app->make(AuditService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Site settings have been removed, no need to check or apply email settings
    }
}
