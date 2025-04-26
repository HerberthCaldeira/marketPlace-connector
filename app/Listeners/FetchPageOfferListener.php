<?php

namespace App\Listeners;

use App\Domains\Offers\Jobs\FetchPageOffersJob;
use App\Events\FetchPageOfferEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * It dispatches a job to fetch offers from a single page.
 */
class FetchPageOfferListener
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
     * @param ImportTaskPage $importTaskPage
     * 
     * @see FetchPageOffersJob
     */
    public function handle(FetchPageOfferEvent $event): void
    {
        logger('FetchPageOfferListener::Fetching offers from a page', ['page_number' => $event->importTaskPage->page_number]);
        FetchPageOffersJob::dispatch($event->importTaskPage);
    }
}
