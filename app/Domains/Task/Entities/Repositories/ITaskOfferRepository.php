<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities\Repositories;

use App\Domains\Task\Entities\OfferEntity;

interface ITaskOfferRepository
{
    public function create(OfferEntity $offerEntity): OfferEntity;

    public function getById(int $id): OfferEntity | null;

    public function getByPageId(int $taskId, string $status): array | null;

    public function update(OfferEntity $offerEntity): OfferEntity;
}
