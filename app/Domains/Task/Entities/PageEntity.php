<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities;

class PageEntity
{
    public function __construct(
        public ?int $id,
        public int $taskId,
        public int $pageNumber,
        public string $status,
    ) {
    }

    public static function create(TaskEntity $taskEntity, int $pageNumber): self
    {
        return new self(
            null,
            $taskEntity->id,
            $pageNumber,
            'pending',
        );
    }
}
