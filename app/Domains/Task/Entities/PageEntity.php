<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities;

use App\Domains\Task\Entities\States\TaskPage\TaskPagePendingState;
use App\Domains\Task\Entities\States\TaskPage\ITaskPageState;

class PageEntity 
{
    public function __construct(
        public ?int $id,
        public int $taskId,
        public int $pageNumber,
        public ?ITaskPageState $status,
    ) {
    }

    public static function create(TaskEntity $taskEntity, int $pageNumber): self
    {
        $pageEntity = new self(
            null,
            $taskEntity->id,
            $pageNumber,
            null,
        );

        $pageEntity->setState(new TaskPagePendingState($pageEntity));
        return $pageEntity;
    }

    public function setState(ITaskPageState $state): void
    {
        $this->status = $state;
    }

    public function getState(): ITaskPageState
    {
        return $this->status;
    }




}
