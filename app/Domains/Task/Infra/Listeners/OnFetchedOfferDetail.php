<?php

namespace App\Domains\Task\Infra\Listeners;

use App\Domains\Task\Entities\Events\FetchedOfferDetailEvent;
use App\Domains\Task\Infra\Jobs\SendOfferToHubJob;

final class OnFetchedOfferDetail
{
    public function __construct() {}

    public function handle(FetchedOfferDetailEvent $event): void
    {
        logger('onFetched::offer', ['id' => $event->offerId]);
        SendOfferToHubJob::dispatch($event->offerId);
    }
}
