<?php

declare(strict_types = 1);

use App\Domains\Offers\Contracts\IMarketingPlaceClient;
use App\Domains\Offers\Jobs\StartImportOffersJob;
use App\Events\ImportOffersRequestedEvent;
use App\Infrastructure\Marketplace\MarketPlaceClient;
use App\Listeners\ImportOffersRequestedListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

it('should be able to dispatch an event when import offer is requested', function (): void {
    Event::fake();

    $response = $this->postJson(route('import.offer'));

    $response->assertStatus(200);

    Event::assertDispatched(ImportOffersRequestedEvent::class);
    Event::assertListening(ImportOffersRequestedEvent::class, ImportOffersRequestedListener::class);
});

it('should be able to push a job to the queue when import offer is requested', function (): void {
    Queue::fake();

    $response = $this->postJson(route('import.offer'));

    $response->assertStatus(200);

    Queue::assertPushed(StartImportOffersJob::class);
});

it('should be able to get a concret class from a typehint interface', function (): void {
    $marketingPlaceClient = app(IMarketingPlaceClient::class);
    expect($marketingPlaceClient)->toBeInstanceOf(MarketPlaceClient::class);
});

it('should be able to get offers from marketplace', function (): void {
    $marketingPlaceClient = app(IMarketingPlaceClient::class);
    $offers               = $marketingPlaceClient->getAllOffersFromAPage(1);
    ds($offers);
    expect($offers)->toBeArray();
});

// it('job', function (): void {

//     new ImportOffersJob()->handle();
// });
