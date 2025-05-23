<?php

declare(strict_types=1);

namespace App\Domains\Task\Entities;

use App\Domains\Task\Entities\States\Task\ITaskState;
use App\Domains\Task\Entities\States\Task\TaskCompletedState;
use App\Domains\Task\Entities\States\Task\TaskFailedState;
use App\Domains\Task\Entities\States\Task\TaskStartedState;

class TaskEntity
{
    public function __construct(
        public ?int $id,
        public ?\DateTimeInterface $startedAt,
        public ?\DateTimeInterface $finishedAt,
        private ?ITaskState $status = null
    ) {}
    
    public static function create(): self
    {
        $startedAt = new \DateTimeImmutable();
        $finishedAt = null;
        $task = new self(null, $startedAt, $finishedAt, null);
        $task->setState(new TaskStartedState($task));
        
        return $task;
    }

    public function getState(): ITaskState
    {
        return $this->status;
    }

    public function setState(ITaskState $status): void
    {
        $this->status = $status;
    }

    public function isCompleted(): bool
    {
        return $this->status instanceof TaskCompletedState;
    }

    public function isFailed(): bool
    {
        return $this->status instanceof TaskFailedState;
    }
}
