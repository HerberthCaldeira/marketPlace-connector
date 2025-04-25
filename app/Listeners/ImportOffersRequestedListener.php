<?php

declare(strict_types = 1);

namespace App\Listeners;

use App\Domains\Offers\Jobs\StartImportOffersJob;
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
        StartImportOffersJob::dispatch();
        logger('Import offers job dispatched');
    }
}
