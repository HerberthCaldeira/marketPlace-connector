<?php

declare(strict_types = 1);

namespace App\Providers;

use App\Domains\Hub\Contracts\IHubClient;
use App\Domains\Offers\Contracts\IMarketingPlaceClient;
use App\Infrastructure\Hub\HubClient;
use App\Infrastructure\Marketplace\MarketPlaceClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(IMarketingPlaceClient::class, MarketPlaceClient::class);
        $this->app->bind(IHubClient::class, HubClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
