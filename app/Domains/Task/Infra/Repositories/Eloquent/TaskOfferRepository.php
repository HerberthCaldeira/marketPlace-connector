<?php

declare(strict_types = 1);

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
            $offerModel->payload,
            $offerModel->sent_at,
            $offerModel->error_message
        );

        return $offerEntity;
    }

    public function getById(int $id): OfferEntity | null
    {
        $model = Offer::query()->where('id', $id)->first();

        if (! $model instanceof Offer) {
            return null;
        }

        return new OfferEntity(
            $model->id,
            $model->task_id,
            $model->task_page_id,
            $model->reference,
            $model->status,
            $model->payload,
            $model->sent_at,
            $model->error_message
        );
    }

    public function getByPageId(int $id, string $status = 'pending'): array | null
    {
        $models = Offer::query()
            ->where('task_page_id', $id)
            ->where('status', $status)
            ->get();

        if ($models->isEmpty()) {
            return null;
        }

        $entities = $models->map(fn (Offer $model): OfferEntity => new OfferEntity(
            $model->id,
            $model->task_id,
            $model->task_page_id,
            $model->reference,
            $model->status,
            $model->payload,
            $model->sent_at,
            $model->error_message
        ))->toArray();

        return $entities;
    }

    public function update(int $id, array $data): OfferEntity
    {
        Offer::query()->where('id', $id)->update($data);

        $model = Offer::query()->where('id', $id)->first();

        $entity = new OfferEntity(
            $model->id,
            $model->task_id,
            $model->task_page_id,
            $model->reference,
            $model->status,
            $model->payload,
            $model->sent_at,
            $model->error_message
        );

        return $entity;
    }
}
