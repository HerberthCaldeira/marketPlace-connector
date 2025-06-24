<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities;

class OfferEntity
{
    public function __construct(
        public ?int $id,
        public int $taskId,
        public int $taskPageId,
        public string $reference,
        public string $status,
        public ?array $payload,
        public ?\DateTimeInterface $sentAt,
        public ?string $errorMessage,
    ) {
    }

    public static function create(
        int $taskId,
        int $taskPageId,
        string $reference,
        string $status,
        ?array $payload = null,
        ?\DateTimeInterface $sentAt = null,
        ?string $errorMessage = null,
    ): OfferEntity {
        return new self(
            null,
            $taskId,
            $taskPageId,
            $reference,
            $status,
            $payload,
            $sentAt,
            $errorMessage,
        );
    }
}
