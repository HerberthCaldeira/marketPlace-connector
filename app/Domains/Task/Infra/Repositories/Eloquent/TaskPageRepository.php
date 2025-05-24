<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Repositories\Eloquent;

use App\Domains\Task\Entities\PageEntity;
use App\Domains\Task\Entities\Repositories\ITaskPageRepository;
use App\Domains\Task\Infra\Models\Page;

class TaskPageRepository implements ITaskPageRepository
{
    public function create(PageEntity $pageEntity): PageEntity
    {
        $pageModel = Page::create([
            "task_id" => $pageEntity->taskId,
            "page_number" => $pageEntity->pageNumber,
            "status" => $pageEntity->status,
        ]);

        $pageEntity = new PageEntity(
            $pageModel->id,
            $pageModel->task_id,
            $pageModel->page_number,
            $pageModel->status,
        );

        return $pageEntity;
    }

    public function getById(int $id): PageEntity | null
    {
        $pageModel = Page::query()->where('id', $id)->first();

        if (! $pageModel instanceof Page) {
            return null;
        }

        return new PageEntity(
            $pageModel->id,
            $pageModel->task_id,
            $pageModel->page_number,
            $pageModel->status,
        );
    }

    public function getByTaskId(int $taskId, string $status = 'pending'): array | null
    {
        $pageModels = Page::query()
            ->where('task_id', $taskId)
            ->where('status', $status)
            ->get();

        if ($pageModels->isEmpty()) {
            return null;
        }

        $pageEntities = $pageModels->map(fn (Page $pageModel): PageEntity => new PageEntity(
            $pageModel->id,
            $pageModel->task_id,
            $pageModel->page_number,
            $pageModel->status,
        ))->toArray();

        return $pageEntities;
    }

    public function update(PageEntity $pageEntity): PageEntity
    {
        Page::query()->where('id', $pageEntity->id)->update([
            'status' => $pageEntity->status,
        ]);

        $pageModel = Page::query()->where('id', $pageEntity->id)->first();

        $pageEntity = new PageEntity(
            $pageModel->id,
            $pageModel->task_id,
            $pageModel->page_number,
            $pageModel->status,
        );

        return $pageEntity;
    }
}
    