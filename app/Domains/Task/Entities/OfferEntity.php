<?php 

namespace App\Domains\Task\Entities;

class OfferEntity {

    public function __construct(
        public int $id,
        public int $taskId,
        public int $taskPageId,
        public string $reference,
        public string $status,
        public ?array $payload,
        public ?\DateTimeInterface $sentAt,
        public ?string $errorMessage,
    ) {}
}