<?php 

namespace App\Domains\Task\Entities\Repositories;

use App\Domains\Task\Entities\OfferEntity;

interface ITaskOfferRepository
{
    public function create(array $data): OfferEntity;  
    public function getById(int $id): OfferEntity | null;
    public function getByPageId(int $taskId, string $status): array | null;
    public function update(int $id, array $data): OfferEntity;  
}
