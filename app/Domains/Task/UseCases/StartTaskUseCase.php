<?php

namespace App\Domains\Task\UseCases;

use App\Domains\Task\Entities\Contracts\Repositories\ITaskRepository;
use App\Domains\Task\UseCases\Contracts\IUseCases;

class StartTaskUseCase implements IUseCases
{
    public function __construct(private ITaskRepository $taskRepository){}

    public function execute(array $data): array
    {
        $taskEntity = $this->taskRepository->create($data);
        

        return [
            'id' => $taskEntity->id,
            'status' => $taskEntity->getState()->getStateName(),
            'startedAt' => $taskEntity->startedAt,
            'finishedAt' => $taskEntity->finishedAt
        ];
    }
}