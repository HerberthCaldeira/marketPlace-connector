<?php 

namespace App\Domains\Task\Entities;

class PageEntity {

    public function __construct(
        public int $id,
        public int $taskId,
        public int $pageNumber,
        public string $status,
        public ?\DateTimeInterface $startedAt,
        public ?\DateTimeInterface $finishedAt,

    ) {}
    
}
