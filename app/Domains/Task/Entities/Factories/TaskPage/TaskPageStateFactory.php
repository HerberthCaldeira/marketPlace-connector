<?php

declare(strict_types=1);

namespace App\Domains\Task\Entities\Factories\TaskPage;

use App\Domains\Task\Entities\PageEntity;
use App\Domains\Task\Entities\States\TaskPage\{
    TaskPageFailedState,
    TaskPageFetchedState,
    TaskPagePendingState
};
use App\Domains\Task\Entities\States\TaskPage\ITaskPageState;
use App\Domains\Task\Entities\States\TaskPage\TaskPageStateEnum;
use InvalidArgumentException;

class TaskPageStateFactory
{
    /**
     * @throws InvalidArgumentException
     */
    public static function create(PageEntity $pageEntity, ?string $state = null): ITaskPageState 
    {
        $state = $state ?? TaskPageStateEnum::PENDING->value;

        return match ($state) {
            TaskPageStateEnum::PENDING->value => new TaskPagePendingState($pageEntity),
            TaskPageStateEnum::FETCHED->value => new TaskPageFetchedState($pageEntity),
            TaskPageStateEnum::FAILED->value => new TaskPageFailedState($pageEntity),
            default => throw new InvalidArgumentException("Invalid state: {$state}"),
        };

    }
}