<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Repositories\Eloquent;

use App\Domains\Task\Entities\Factories\Task\TaskStateFactory;
use App\Domains\Task\Entities\Repositories\ITaskRepository;
use App\Domains\Task\Entities\TaskEntity;
use App\Domains\Task\Infra\Models\Task;

class TaskRepository implements ITaskRepository
{
    public function create(array $data): TaskEntity
    {
        $taskModel = Task::create($data);

        $taskEntity = new TaskEntity(
            $taskModel->id,
            $taskModel->startedAt,
            $taskModel->finishedAt
        );

        $taskEntity->setState(TaskStateFactory::create($taskEntity, $taskModel->status));

        return $taskEntity;
    }
}
