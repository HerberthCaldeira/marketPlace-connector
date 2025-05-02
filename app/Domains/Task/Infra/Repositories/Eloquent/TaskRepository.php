<?php 

namespace App\Infrastructure\Task\Repositories\Eloquent;

use App\Domains\Task\Entities\Contracts\Repositories\ITaskRepository;
use App\Domains\Task\Entities\Factories\Task\TaskStateFactory;
use App\Domains\Task\Entities\TaskEntity;
use App\Models\Task;

class TaskEloquentRepository implements ITaskRepository
{
    public function create(array $data): TaskEntity
    {
        $taskModel = Task::create($data);
        
        $taskEntity = new TaskEntity(
            $taskModel->id,
            $taskModel->status,
            $taskModel->startedAt,
            $taskModel->finishedAt
        );

        $taskEntity->setState(TaskStateFactory::create($taskModel->status, $taskEntity));

        return $taskEntity;
    }



}
