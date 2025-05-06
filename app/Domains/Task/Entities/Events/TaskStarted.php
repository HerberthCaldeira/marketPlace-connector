<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities\Events;

use App\Domains\Task\Entities\TaskEntity;

class TaskStarted
{
    public function __construct(
        public readonly TaskEntity $task
    ) {
    }
}
