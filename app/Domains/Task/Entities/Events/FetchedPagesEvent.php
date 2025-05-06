<?php

namespace App\Domains\Task\Entities\Events;

use App\Domains\Task\Entities\TaskEntity;

class FetchedPagesEvent {

    public function __construct(
        public readonly int $taskId
    ) {}
    
}