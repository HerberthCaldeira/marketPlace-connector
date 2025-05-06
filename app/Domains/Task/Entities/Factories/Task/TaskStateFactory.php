<?php 

namespace App\Domains\Task\Entities\Factories\Task;

use App\Domains\Task\Entities\States\Task\ITaskState;
use App\Domains\Task\Entities\States\Task\TaskCompletedState;
use App\Domains\Task\Entities\States\Task\TaskFailedState;
use App\Domains\Task\Entities\States\Task\TaskStartedState;
use App\Domains\Task\Entities\States\Task\TaskStateEnum;
use App\Domains\Task\Entities\TaskEntity;

class TaskStateFactory
{
    public static function create(?string $state =null, TaskEntity $task): ITaskState
    {
        return match($state) {
            TaskStateEnum::STARTED->value => new TaskStartedState($task),
            TaskStateEnum::COMPLETED->value => new TaskCompletedState($task),
            TaskStateEnum::FAILED->value => new TaskFailedState($task),
            default =>  new TaskStartedState($task),
        };
    }
}