<?php

declare(strict_types = 1);

namespace App\Listeners;

use App\Domains\Offers\Jobs\StartImportOffersJob;
use App\Events\ImportOffersRequestedEvent;
use App\Models\ImportTask;

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
        //create as status padding
        $importTask = ImportTask::create();

        logger('Starting import', ['importTaskId' => $importTask->id]);
        StartImportOffersJob::dispatch($importTask);
    }
}
