<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Repositories\Eloquent;

use App\Domains\Task\Entities\OfferEntity;
use App\Domains\Task\Entities\Repositories\ITaskOfferRepository;
use App\Domains\Task\Infra\Models\Offer;

class TaskOfferRepository implements ITaskOfferRepository
{
    public function create(OfferEntity $offerEntity): OfferEntity
    {
        $offerModel = Offer::query()->create([
            'task_id'      => $offerEntity->taskId,
            'task_page_id' => $offerEntity->taskPageId,
            'reference'    => $offerEntity->reference,
            'status'       => $offerEntity->status,
            'payload'      => $offerEntity->payload,
            'sent_at'      => $offerEntity->sentAt,
            'error_message' => $offerEntity->errorMessage,
        ]);

        $offerEntity = new OfferEntity(
            $offerModel->id,
            $offerModel->task_id,
            $offerModel->task_page_id,
            $offerModel->reference,
            $offerModel->status,
            $offerModel->payload,
            $offerModel->sent_at ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $offerModel->sent_at) : null,
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
            $model->sent_at ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->sent_at): null,
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
            $model->sent_at ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->sent_at): null,
            $model->error_message
        ))->toArray();

        return $entities;
    }

    public function update(OfferEntity $offerEntity): OfferEntity
    {
        Offer::query()->where('id', $offerEntity->id)->update([
            'payload' => $offerEntity->payload,
            'status'  => $offerEntity->status,
        ]);

        $model = Offer::query()->where('id', $offerEntity->id)->first();

        $entity = new OfferEntity(
            $model->id,
            $model->task_id,
            $model->task_page_id,
            $model->reference,
            $model->status,
            $model->payload,
            $model->sent_at ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $model->sent_at): null,
            $model->error_message
        );

        return $entity;
    }
}
