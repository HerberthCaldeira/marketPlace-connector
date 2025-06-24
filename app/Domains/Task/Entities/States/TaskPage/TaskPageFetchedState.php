<?php
namespace App\Domains\Task\Entities\States\TaskPage;

use App\Domains\Task\Entities\PageEntity;
use App\Domains\Task\Entities\States\TaskPage\ITaskPageState;
use App\Domains\Task\Entities\States\TaskPage\TaskPageStateEnum;

class TaskPageFetchedState implements ITaskPageState
{
    //@phpstan-ignore-next-line
    public function __construct(private readonly PageEntity $page)
    {
    }
    
    public function getStateName(): string
    {
        return TaskPageStateEnum::FETCHED->value;
    }

    public function fetched(): void
    {
        throw new \Exception('Already fetched cannot be fetched again');
    }

    public function pending(): void
    {
        throw new \Exception('Already pending cannot be fetched');
    }

    public function failed(): void
    {
        throw new \Exception('Already failed cannot be fetched');
    }
}

