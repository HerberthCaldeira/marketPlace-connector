<?php

declare(strict_types = 1);

namespace App\Providers;

use App\Domains\Hub\Contracts\IHubClient;
use App\Domains\SharedKernel\Events\Dispatcher\IEventDispatcher;
use App\Domains\SharedKernel\Events\LaravelEventDispatcher;
use App\Domains\Task\Entities\Events\FetchedPagesEvent;
use App\Domains\Task\Entities\Events\TaskStarted;
use App\Domains\Task\Entities\Gateways\IMarketingPlaceClient;
use App\Domains\Task\Entities\Repositories\ITaskOfferRepository;
use App\Domains\Task\Entities\Repositories\ITaskPageRepository;
use App\Domains\Task\Entities\Repositories\ITaskRepository;
use App\Domains\Task\Infra\Gateways\MarketingPlaceClient;
use App\Domains\Task\Infra\Listeners\OnFetchedPages;
use App\Domains\Task\Infra\Listeners\OnStartedTask;
use App\Domains\Task\Infra\Repositories\Eloquent\TaskOfferRepository;
use App\Domains\Task\Infra\Repositories\Eloquent\TaskPageRepository;
use App\Domains\Task\Infra\Repositories\Eloquent\TaskRepository;
use App\Infrastructure\Hub\HubClient;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //Shared Kernel
        $this->app->bind(IEventDispatcher::class, LaravelEventDispatcher::class);
        //Task
        $this->app->bind(ITaskRepository::class, TaskRepository::class);
        $this->app->bind(ITaskPageRepository::class, TaskPageRepository::class);
        $this->app->bind(ITaskOfferRepository::class, TaskOfferRepository::class);

        $this->app->bind(IMarketingPlaceClient::class, MarketingPlaceClient::class);
        $this->app->bind(IHubClient::class, HubClient::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Event::listen(TaskStarted::class, OnStartedTask::class);
        Event::listen(FetchedPagesEvent::class, OnFetchedPages::class);
    }
}
