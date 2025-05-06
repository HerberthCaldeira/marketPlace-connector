<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities\Events;

final class FetchedOfferDetailEvent
{
    public function __construct(
        public int $offerId
    ) {
    }
}
