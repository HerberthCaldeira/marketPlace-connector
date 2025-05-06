<?php 

namespace App\Domains\Task\Entities\Repositories;

use App\Domains\Task\Entities\OfferEntity;

interface ITaskOfferRepository
{
    public function create(array $data): OfferEntity;    
}
