<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\UserRepository;
use App\Repositories\FundRequestRepository;
use App\Repositories\DocumentRepository;
use App\Repositories\ScholarProfileRepository;
use App\Models\User;
use App\Models\FundRequest;
use App\Models\Document;
use App\Models\ScholarProfile;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register repositories
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository(new User());
        });

        $this->app->bind(FundRequestRepository::class, function ($app) {
            return new FundRequestRepository(new FundRequest());
        });

        $this->app->bind(DocumentRepository::class, function ($app) {
            return new DocumentRepository(new Document());
        });

        $this->app->bind(ScholarProfileRepository::class, function ($app) {
            return new ScholarProfileRepository(new ScholarProfile());
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
