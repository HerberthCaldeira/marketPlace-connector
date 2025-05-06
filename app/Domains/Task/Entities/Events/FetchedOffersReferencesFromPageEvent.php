<?php

namespace App\Domains\Task\Entities\Events;


class FetchedOffersReferencesFromPageEvent {

    public function __construct(
        public readonly int $pageId
    ) {}
    
}