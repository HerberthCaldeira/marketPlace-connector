<?php 
namespace App\Domains\Task\Entities\States\TaskPage;

use App\Domains\Task\Entities\States\TaskPage\ITaskPageState;
use App\Domains\Task\Entities\States\TaskPage\TaskPageFetchedState;
use App\Domains\Task\Entities\States\TaskPage\TaskPageStateEnum;
use App\Domains\Task\Entities\PageEntity;

class TaskPagePendingState implements ITaskPageState
{
    public function __construct(private readonly PageEntity $page)
    {
    }
    
    public function getStateName(): string
    {
        return TaskPageStateEnum::PENDING->value;
    }

    public function fetched(): void
    {
        $this->page->setState(new TaskPageFetchedState($this->page));
    }

    public function pending(): void
    {
        throw new \Exception('Already pending cannot be fetched');
    }

    public function failed(): void
    {
        $this->page->setState(new TaskPageFailedState($this->page));
    }
}