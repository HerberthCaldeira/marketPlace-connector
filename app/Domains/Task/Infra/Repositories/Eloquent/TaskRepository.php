<?php

declare(strict_types=1);

namespace App\Domains\Task\Infra\Repositories\Eloquent;

use App\Domains\Task\Entities\Factories\Task\TaskStateFactory;
use App\Domains\Task\Entities\Repositories\ITaskRepository;
use App\Domains\Task\Entities\TaskEntity;
use App\Domains\Task\Infra\Models\Task;

class TaskRepository implements ITaskRepository
{
    public function create(TaskEntity $task): TaskEntity
    {
        $taskModel = Task::query()->create([
            "status" => $task->getState()->getStateName(),
            "started_at" => $task->startedAt->format('Y-m-d H:i:s'),
        ]);

        $taskEntity = new TaskEntity(
            $taskModel->id,
            new \DateTimeImmutable($taskModel->started_at->format('Y-m-d H:i:s')),
            blank($taskModel->finished_at) ? null : new \DateTimeImmutable($taskModel->finished_at->format('Y-m-d H:i:s'))
        );

        $taskEntity->setState(
            TaskStateFactory::create($taskEntity, $taskModel->status)
        );

        return $taskEntity;
    }

    public function getById(int $id): TaskEntity
    {
        $taskModel = Task::query()->find($id);
        $taskEntity = new TaskEntity(
            $taskModel->id,
            new \DateTimeImmutable($taskModel->started_at->format('Y-m-d H:i:s')),
            blank($taskModel->finished_at) ? null : new \DateTimeImmutable($taskModel->finished_at->format('Y-m-d H:i:s'))
        );
        $taskEntity->setState(
            TaskStateFactory::create($taskEntity, $taskModel->status)
        );
        return $taskEntity;
    }
}
