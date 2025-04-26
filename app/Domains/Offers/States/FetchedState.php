<?php

declare(strict_types=1);

namespace App\Domains\Offers\States;

use App\Events\SendOfferToHubEvent;

class FetchedState extends OfferState
{

    public function sendToHub(): void
    {
        /**
         * @see App\Listeners\SendOfferToHubListener
         */
        event(new SendOfferToHubEvent($this->importTaskOffer));
     
    }

    public function fail(string $error): void
    {
        $this->importTaskOffer->update(['status' => 'failed']);
        $this->importTaskOffer->setState(new FailedState($this->importTaskOffer));
    }

    public function getStatus(): string
    {
        return 'fetched';
    }
}
