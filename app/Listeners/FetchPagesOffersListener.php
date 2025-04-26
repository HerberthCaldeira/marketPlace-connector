<?php

namespace App\Listeners;

use App\Domains\Offers\Jobs\FetchPageOffersJob;
use App\Events\FetchPagesOffersEvent;
use Illuminate\Support\Facades\Bus;

/**
 * It dispatches jobs to fetch offers from all pages of the import task using a batch strategy.
 * 
 * @param ImportTask $importTask
 * 
 * @see FetchPageOffersJob
 */
class FetchPagesOffersListener
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
    public function handle(FetchPagesOffersEvent $event): void
    {
        logger('FetchPageOffersListener::Fetch pages offers using batch strategy', ['importTaskId' => $event->importTask->id]);

        $batchJobs = [];
        $importTask = $event->importTask;
        $importTaskPages = $importTask->pages()->get();

        foreach ($importTaskPages as $importTaskPage) {
            $batchJobs[] = new FetchPageOffersJob($importTaskPage);
        }

        Bus::batch($batchJobs)
            ->then(function () use ($importTask): void {
                $importTask->update(['status' => 'completed']);
                logger('StartImportOffersJob::Batch success.', ['importTaskId' => $importTask->id]);
            })->catch(function () use ($importTask): void {
                $importTask->update(['status' => 'failed']);
                logger('StartImportOffersJob::Batch failed.', ['importTaskId' => $importTask->id]);
            })->finally(function () use ($importTask): void {
                $importTask->update(['finished_at' => now()]);
                logger('StartImportOffersJob::Batch finished.', ['importTaskId' => $importTask->id]);
            })->dispatch();
    }
}
