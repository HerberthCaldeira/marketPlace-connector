<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities\States\Task;

enum TaskStateEnum: string
{
    case STARTED   = 'started';
    case COMPLETED = 'completed';
    case FAILED    = 'failed';
}
