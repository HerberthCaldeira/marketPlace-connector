<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Listeners;

use App\Domains\Task\Entities\Events\TaskStarted;
use App\Domains\Task\UseCases\FetchPagesUseCase;
use Illuminate\Contracts\Queue\ShouldQueue;

final class OnStartedTask implements ShouldQueue
{
    public function __construct(private FetchPagesUseCase $useCase){}

    public function handle(TaskStarted $event): void
    {
        logger('onStarted::task', ['id' => $event->task->id]);
        $this->useCase->execute($event->task->id, $event->page);
    }
}
