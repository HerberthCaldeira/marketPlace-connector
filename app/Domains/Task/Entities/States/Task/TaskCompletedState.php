<?php

namespace App\Domains\Task\Entities\States\Task;

use App\Domains\Task\Entities\TaskEntity;

class TaskCompletedState implements ITaskState
{
    public function __construct(private TaskEntity $task)
    {
    }

    public function getStateName(): string
    {
        return TaskStateEnum::COMPLETED->value;
    }

    public function started(): void
    {
        throw new \Exception('Already completed cannot be started again');
    }

    public function completed(): void
    {
        throw new \Exception('Already completed cannot be completed again');
    }

    public function failed(): void
    {
        throw new \Exception('Already completed cannot be failed');
    }
}
