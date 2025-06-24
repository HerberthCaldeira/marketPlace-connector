<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities\States\Task;

use App\Domains\Task\Entities\TaskEntity;

class TaskFailedState implements ITaskState
{
    public function __construct(private readonly TaskEntity $task)
    {
    }

    public function getStateName(): string
    {
        return TaskStateEnum::FAILED->value;
    }

    public function started(): void
    {
        $this->task->setState(new TaskStartedState($this->task));
    }

    public function completed(): void
    {     
        $this->task->setState(new TaskCompletedState($this->task));
    }

    public function failed(): void
    {
        throw new \Exception('Task is already failed');
    }
}
