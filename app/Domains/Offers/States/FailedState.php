<?php

declare(strict_types=1);

namespace App\Domains\Offers\States;

class FailedState extends OfferState
{
    public function fetch(): void
    {
        // Can retry fetch
        $this->offer->setState(new PendingState($this->offer));
        $this->offer->getState()->fetch();
    }

    public function sendToHub(): void
    {
        throw new \Exception('Cannot send failed offer to hub');
    }

    public function fail(string $error): void
    {
        // Already failed
    }

    public function getStatus(): string
    {
        return 'failed';
    }
}
