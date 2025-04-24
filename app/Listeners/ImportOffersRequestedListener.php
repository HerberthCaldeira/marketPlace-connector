<?php

declare(strict_types = 1);

namespace App\Listeners;

use App\Domains\Offers\Jobs\ImportOffersJob;
use App\Events\ImportOffersRequestedEvent;

class ImportOffersRequestedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ImportOffersRequestedEvent $event): void
    {
        ImportOffersJob::dispatch();
    }
}
