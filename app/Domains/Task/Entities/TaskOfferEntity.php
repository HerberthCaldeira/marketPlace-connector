<?php 

namespace App\Domains\Task\Entities;

class TaskOfferEntity {

    public function __construct(
        public int $id,
        public int $importTaskId,
        public int $importTaskPageId,
        public string $reference,
        public string $status,
        public ?\DateTimeInterface $sentAt,
        public ?string $errorMessage,
    ) {}
}