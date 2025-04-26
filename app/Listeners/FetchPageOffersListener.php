<?php

namespace App\Listeners;

use App\Domains\Offers\Jobs\FetchPageOffersJob;
use App\Events\FetchPageOffersEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Bus;

/**
It is responsible for dispatching the job to fetch the offers from a page.
 * It will use a batch strategy to fetch the offers from all pages.
 * 
 * @param ImportTask $importTask
 * 
 * @see FetchPageOffersJob
 */
class FetchPageOffersListener
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
    public function handle(FetchPageOffersEvent $event): void
    {
        logger('FetchPageOffersListener::Fetch pages offers using batch strategy', ['importTaskId' => $event->importTask->id]);

        $batchJobs = [];
        $importTask = $event->importTask;
        $pages = $importTask->pages()->get();

        foreach ($pages as $page) {
            $batchJobs[] = new FetchPageOffersJob($importTask, $page);
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
