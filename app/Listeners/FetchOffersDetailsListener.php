<?php

namespace App\Listeners;

use App\Domains\Hub\Jobs\SendOfferToHubJob;
use App\Domains\Offers\Jobs\FetchOfferDetailJob;
use App\Events\FetchOffersDetailsEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Bus;

/**
 * It's responsible for dispatching the job to fetch all the offers details from a page.
 * It will use a batch strategy to fetch all offers details from a page.
 * 
 * 
 */
class FetchOffersDetailsListener
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
     * @param FetchOffersDetailsEvent $event
     * 
     */
    public function handle(FetchOffersDetailsEvent $event): void
    {
        logger('FetchOffersDetailsListener::Fetch offers details using batch', ['importTaskPageId' => $event->importTaskPage->id, 'page_number' => $event->importTaskPage->page_number]);
        $importTaskPage = $event->importTaskPage;
        $offers = $importTaskPage->offers()->get();

        $batchJobs = [];

        /** @var \App\Models\ImportTaskOffer $offer */
        foreach ($offers as $offer) {
            $batchJobs[] = new FetchOfferDetailJob($offer);
        }

        Bus::batch($batchJobs)
            ->then(function () use ($importTaskPage): void {
                $importTaskPage->update(['status' => 'completed']);
                logger('FetchPageOffersJob::Batch success.', ['importTaskPageId' => $importTaskPage->id, 'page_number' => $importTaskPage->page_number]);
            })->catch(function () use ($importTaskPage): void {
                $importTaskPage->update(['status' => 'failed']);
                logger('FetchPageOffersJob::Batch failed.', ['importTaskPageId' => $importTaskPage->id, 'page_number' => $importTaskPage->page_number]);
            })->finally(function () use ($importTaskPage): void {
                $importTaskPage->update(['finished_at' => now()]);
                logger('FetchPageOffersJob::Batch finished.', ['importTaskPageId' => $importTaskPage->id, 'page_number' => $importTaskPage->page_number]);
            })->dispatch();
    }
}
