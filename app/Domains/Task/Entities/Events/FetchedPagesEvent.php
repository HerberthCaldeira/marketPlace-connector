<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities\Events;

class FetchedPagesEvent
{
    public function __construct(
        public readonly int $taskId
    ) {
    }
}
