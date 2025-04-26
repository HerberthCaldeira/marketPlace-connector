<?php

declare(strict_types = 1);

namespace App\Listeners;

use App\Domains\Offers\Jobs\FetchPageOffersJob;
use App\Events\FetchPagesEvent;
use Illuminate\Support\Facades\Bus;

/**
 * It dispatches jobs to fetch offers from all pages of the import task using a batch strategy.
 *
 */
class FetchPagesListener
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
     *
     * @param FetchPagesEvent $event
     *
     */
    public function handle(FetchPagesEvent $event): void
    {
        logger('FetchPagesListener::Fetch pages offers using batch strategy', ['importTaskId' => $event->importTask->id]);

        $batchJobs       = [];
        $importTask      = $event->importTask;
        $importTaskPages = $importTask->pages()->get();

        /** @var \App\Models\ImportTaskPage $importTaskPage */
        foreach ($importTaskPages as $importTaskPage) {
            $batchJobs[] = new FetchPageOffersJob($importTaskPage);
        }

        Bus::batch($batchJobs)
            ->then(function () use ($importTask): void {
                $importTask->update(['status' => 'completed']);
                logger('FetchPageOffersJob::Batch success.', ['importTaskId' => $importTask->id]);
            })->catch(function () use ($importTask): void {
                $importTask->update(['status' => 'failed']);
                logger('FetchPagesListener::Batch failed.', ['importTaskId' => $importTask->id]);
            })->finally(function () use ($importTask): void {
                $importTask->update(['finished_at' => now()]);
                logger('FetchPagesListener::Batch finished.', ['importTaskId' => $importTask->id]);
            })->dispatch();
    }
}
