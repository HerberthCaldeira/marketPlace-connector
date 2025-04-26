<?php

declare(strict_types = 1);

namespace App\Listeners;

use App\Domains\Hub\Jobs\SendOfferToHubJob;
use App\Events\SendOfferToHubEvent;

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
