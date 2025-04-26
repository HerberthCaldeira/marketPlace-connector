<?php

namespace App\Listeners;

use App\Domains\Hub\Jobs\SendOfferToHubJob;
use App\Events\SendOfferToHubEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
/**
 * It's responsible for dispatching the job to send an offer to the hub.
 * 
 * @param ImportTaskOffer $importTaskOffer
 * 
 */
class SendOfferToHubListener
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
    public function handle(SendOfferToHubEvent $event): void
    {
        SendOfferToHubJob::dispatch($event->importTaskOffer);
    }
}
