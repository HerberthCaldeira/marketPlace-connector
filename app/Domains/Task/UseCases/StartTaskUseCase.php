<?php

declare(strict_types = 1);

namespace App\Domains\Task\UseCases;

use App\Domains\SharedKernel\Contracts\IUseCase;
use App\Domains\SharedKernel\Events\Dispatcher\IEventDispatcher;
use App\Domains\Task\Entities\Events\TaskStarted;
use App\Domains\Task\Entities\Repositories\ITaskRepository;

class StartTaskUseCase implements IUseCase
{
    public function __construct(
        private readonly ITaskRepository $taskRepository,
        private readonly IEventDispatcher $eventDispatcher
    ) {
    }

    public function execute(array $data): array
    {
        $taskEntity = $this->taskRepository->create($data);

        $this->eventDispatcher->dispatch(new TaskStarted($taskEntity));

        return [
            'id'         => $taskEntity->id,
            'status'     => $taskEntity->getState()->getStateName(),
            'startedAt'  => $taskEntity->startedAt,
            'finishedAt' => $taskEntity->finishedAt,
        ];
    }
}
