<?php

namespace App\Domains\Task\UseCases;

use App\Domains\SharedKernel\Events\Dispatcher\IEventDispatcher;
use App\Domains\Task\Entities\Events\TaskStarted;
use App\Domains\Task\Entities\Repositories\ITaskRepository;
use App\Domains\SharedKernel\Contracts\IUseCase;

class StartTaskUseCase implements IUseCase
{
    public function __construct(
        private ITaskRepository $taskRepository, 
        private IEventDispatcher $eventDispatcher
    ){}

    public function execute(array $data): array
    {
        $taskEntity = $this->taskRepository->create($data);      

        $this->eventDispatcher->dispatch(new TaskStarted($taskEntity));

        return [
            'id' => $taskEntity->id,
            'status' => $taskEntity->getState()->getStateName(),
            'startedAt' => $taskEntity->startedAt,
            'finishedAt' => $taskEntity->finishedAt
        ];
    }
}