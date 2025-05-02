<?php

namespace App\Domains\Task\Entities\States\Task;

use App\Domains\Task\Entities\States\Task\TaskCompletedState;
use App\Domains\Task\Entities\States\Task\TaskFailedState;
use App\Domains\Task\Entities\TaskEntity;

class TaskStartedState implements ITaskState
{
    public function __construct(private TaskEntity $task){}

    public function getStateName(): string
    {
        return TaskStateEnum::STARTED->value;
    }
    
    public function started(): void
    {
        throw new \Exception('Task is already started');
    }

    public function completed(): void
    {
        $this->task->setState(new TaskCompletedState($this->task)); 
    }

    public function failed(): void
    {
        $this->task->setState(new TaskFailedState($this->task));
    }
}
