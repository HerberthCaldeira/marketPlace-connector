<?php

declare(strict_types = 1);

use App\Domains\Offers\Jobs\ImportOffersJob;
use App\Events\ImportOffersRequestedEvent;
use App\Listeners\ImportOffersRequestedListener;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

it('should be able to dispatch an event when import offer is requested', function () {
    Event::fake();

    $response = $this->postJson(route('import.offer'));

    $response->assertStatus(200);

    Event::assertDispatched(ImportOffersRequestedEvent::class);
    Event::assertListening(ImportOffersRequestedEvent::class, ImportOffersRequestedListener::class);
});

it('should be able to push a job to the queue when import offer is requested', function () {
    Queue::fake();

    $response = $this->postJson(route('import.offer'));

    $response->assertStatus(200);

    Queue::assertPushed(ImportOffersJob::class);
});
