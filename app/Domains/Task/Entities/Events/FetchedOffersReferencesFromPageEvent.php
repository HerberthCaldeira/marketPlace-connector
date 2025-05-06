<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities\Events;

class FetchedOffersReferencesFromPageEvent
{
    public function __construct(
        public readonly int $pageId
    ) {
    }
}
