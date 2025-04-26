<?php

declare(strict_types=1);

namespace App\Domains\Offers\States;

use App\Events\SendOfferToHubEvent;
use App\Domains\Offers\Services\OffersService;

class PendingState extends OfferState
{   
    public function sendToHub(): void
    {
        throw new \Exception('Cannot send to hub while in pending state');
    }

    public function fail(string $error): void
    {
        $this->importTaskOffer->update(['status' => 'failed']);
        $this->importTaskOffer->setState(new FailedState($this->importTaskOffer));
    }

    public function getStatus(): string
    {
        return 'pending';
    }
}
