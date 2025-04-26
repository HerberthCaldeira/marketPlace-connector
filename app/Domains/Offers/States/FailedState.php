<?php

declare(strict_types = 1);

namespace App\Domains\Offers\States;
/**
 * Implements the state pattern for failed offers.
 */
class FailedState extends OfferState
{    

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
