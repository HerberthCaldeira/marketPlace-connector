<?php

namespace App\Domains\Task\Entities\States\TaskPage;

use App\Domains\Task\Entities\States\TaskPage\ITaskPageState;
use App\Domains\Task\Entities\States\TaskPage\TaskPageStateEnum;
use App\Domains\Task\Entities\PageEntity;

class TaskPageFailedState implements ITaskPageState
{
    //phpstan-ignore-next-line
    public function __construct(private readonly PageEntity $page)
    {
    }
    
    public function getStateName(): string
    {
        return TaskPageStateEnum::FAILED->value;
    }

    public function fetched(): void
    {
        $this->page->setState(new TaskPageFetchedState($this->page));
    }

    public function pending(): void
    {
        $this->page->setState(new TaskPagePendingState($this->page));
    }

    public function failed(): void
    {
        throw new \Exception('Already failed cannot be fetched');
    }
}
