<?php

declare(strict_types = 1);

namespace App\Listeners;

use App\Domains\Offers\Jobs\StartImportOffersJob;
use App\Events\StartImportOffersEvent;
use App\Models\ImportTask;

/**
 * It's responsible for dispatching the job to start the import process.
 * 
 * @param ImportTask $importTask
 * 
 */
class StartImportOffersListener
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
    public function handle(StartImportOffersEvent $event): void
    {
        StartImportOffersJob::dispatch($event->importTask);
    }
}
