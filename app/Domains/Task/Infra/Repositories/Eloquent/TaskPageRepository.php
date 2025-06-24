<?php

declare(strict_types = 1);

namespace App\Domains\Task\Infra\Repositories\Eloquent;

use App\Domains\Task\Entities\Factories\TaskPage\TaskPageStateFactory;
use App\Domains\Task\Entities\PageEntity;
use App\Domains\Task\Entities\Repositories\ITaskPageRepository;
use App\Domains\Task\Infra\Models\Page;

class TaskPageRepository implements ITaskPageRepository
{
    private function fromModel(Page $pageModel): PageEntity
    {
        $pageEntity = new PageEntity(
            $pageModel->id,
            $pageModel->task_id,
            $pageModel->page_number,
            null,
        );

        $pageEntity->status = TaskPageStateFactory::create($pageEntity, $pageModel->status);

        return $pageEntity;
    }

    public function create(PageEntity $pageEntity): PageEntity
    {
        $pageModel = Page::query()->create([
            "task_id" => $pageEntity->taskId,
            "page_number" => $pageEntity->pageNumber,
            "status" => $pageEntity->getState()->getStateName(),
        ]);

        $pageEntity = $this->fromModel($pageModel);

        return $pageEntity;
    }

    public function getById(int $id): PageEntity | null
    {
        $pageModel = Page::query()->where('id', $id)->first();

        if (! $pageModel instanceof Page) {
            return null;
        }

        $pageEntity = $this->fromModel($pageModel);

        return $pageEntity;
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

        $pageEntities = $pageModels->map(function (Page $pageModel){
            $pageEntity = $this->fromModel($pageModel);
            return $pageEntity;
        })->toArray();

        return $pageEntities;
    }

    public function update(PageEntity $pageEntity): PageEntity
    {
        Page::query()->where('id', $pageEntity->id)->update([
            'status' => $pageEntity->getState()->getStateName(),
        ]);

        $pageModel = Page::query()->where('id', $pageEntity->id)->first();

        $pageEntity = $this->fromModel($pageModel);

        return $pageEntity;
    }
}

