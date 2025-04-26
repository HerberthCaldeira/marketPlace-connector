<?php

declare(strict_types=1);

namespace App\Domains\Offers\States;

class SentToHubState extends OfferState
{  
    public function sendToHub(): void
    {
        // Already sent to hub
    }

    public function fail(string $error): void
    {
        $this->importTaskOffer->update(['status' => 'failed']);
        $this->importTaskOffer->setState(new FailedState($this->importTaskOffer));
    }

    public function getStatus(): string
    {
        return 'sent_to_hub';
    }
}
