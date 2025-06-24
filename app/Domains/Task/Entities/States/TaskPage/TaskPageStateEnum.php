<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities\States\TaskPage;

enum TaskPageStateEnum: string
{
    case PENDING   = 'pending';
    case FETCHED = 'fetched';
    case FAILED    = 'failed';
}
