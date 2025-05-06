<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Listeners;

use App\Domains\Task\Entities\Events\TaskStarted;
use App\Domains\Task\Infra\Jobs\FetchPagesJob;

final class OnStartedTask
{
    public function __construct()
    {
    }

    public function handle(TaskStarted $event): void
    {
        logger('onStarted::task', ['id' => $event->task->id]);
        FetchPagesJob::dispatch($event->task->id);
    }
}
