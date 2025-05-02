<?php 

namespace App\Domains\Task\Entities;

class TaskPageEntity {

    public function __construct(
        public int $id,
        public int $importTaskId,
        public int $pageNumber,
        public string $status,
        public ?\DateTimeInterface $startedAt,
        public ?\DateTimeInterface $finishedAt,
        public ?string $errorMessage,
    ) {}
    
}
