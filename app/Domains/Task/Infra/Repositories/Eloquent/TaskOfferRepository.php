<?php

namespace App\Domains\Task\Infra\Repositories\Eloquent;

use App\Domains\Task\Entities\OfferEntity;
use App\Domains\Task\Entities\Repositories\ITaskOfferRepository;
use App\Domains\Task\Infra\Models\Offer;

class TaskOfferRepository implements ITaskOfferRepository
{
    public function create(array $data): OfferEntity
    {

        $offerModel = Offer::create($data);        
        
        $offerEntity = new OfferEntity(
            $offerModel->id,   
            $offerModel->task_id,
            $offerModel->task_page_id,
            $offerModel->reference,
            $offerModel->status,
            $offerModel->sent_at,
            $offerModel->error_message
        );

        return $offerEntity; 

    }
}
