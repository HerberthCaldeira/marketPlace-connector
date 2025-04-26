<?php

namespace App\Listeners;

use App\Domains\Offers\Jobs\FetchOfferDetailJob;
use App\Events\FetchOfferDetailEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

/**
 * It's responsible for dispatching the job to fetch the details of an offer.  
 */
class FetchOfferDetailListener
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
     * @param FetchOfferDetailEvent $event
     * 
     */
    public function handle(FetchOfferDetailEvent $event): void
    {
        //
        FetchOfferDetailJob::dispatch($event->importTaskOffer);
    }
}
