<?php

declare(strict_types=1);

namespace App\Domains\Offers\States;

use App\Models\ImportTaskOffer;

abstract class OfferState
{
    public function __construct(protected ImportTaskOffer $importTaskOffer)
    {
    }

    abstract public function sendToHub(): void;
    abstract public function fail(string $error): void;
    abstract public function getStatus(): string;
}
