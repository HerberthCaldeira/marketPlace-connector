<?php


namespace App\Domains\Task\Infra\Repositories\Eloquent;

use App\Domains\Task\Entities\PageEntity;
use App\Domains\Task\Entities\Repositories\ITaskPageRepository;
use App\Domains\Task\Infra\Models\Page;

class TaskPageRepository implements ITaskPageRepository
{
    public function create(array $data): PageEntity
    {
        $pageModel = Page::create($data);
        
        $pageEntity = new PageEntity(
            $pageModel->id,
            $pageModel->task_id,
            $pageModel->page_number,
            $pageModel->status,
            $pageModel->started_at,
            $pageModel->finished_at
        );

        return $pageEntity;
    }    

    public function getById(int $id): PageEntity | null
    {
        $pageModel = Page::query()->where('id', $id)->first();

        if(! $pageModel instanceof Page){
            return null;            
        }
        
        return new PageEntity(
            $pageModel->id,
            $pageModel->task_id,
            $pageModel->page_number,
            $pageModel->status,
            $pageModel->started_at,
            $pageModel->finished_at
        );

    }

    public function getByTaskId(int $taskId, string $status = 'pending'): array | null
    {
        $pageModels = Page::query()
            ->where('task_id', $taskId)
            ->where('status', $status)
            ->get();

        if( $pageModels->isEmpty()){
            return null;
        }

        $pageEntities = $pageModels->map(function (Page $pageModel) {
            return new PageEntity(
                $pageModel->id,
                $pageModel->task_id,
                $pageModel->page_number,
                $pageModel->status,
                $pageModel->started_at,
                $pageModel->finished_at
            );
        })->toArray();

        return $pageEntities;
    }

    public function update(int $id, array $data): PageEntity {

        Page::query()->where('id', $id)->update($data);

        $pageModel = Page::query()->where('id', $id)->first();

        $pageEntity = new PageEntity(
            $pageModel->id,
            $pageModel->task_id,
            $pageModel->page_number,
            $pageModel->status,
            $pageModel->started_at,
            $pageModel->finished_at
        );

        return $pageEntity;
    }
}
    
