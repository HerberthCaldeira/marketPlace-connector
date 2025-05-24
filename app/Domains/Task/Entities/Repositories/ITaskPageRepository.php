<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities\Repositories;

use App\Domains\Task\Entities\PageEntity;

interface ITaskPageRepository
{
    public function create(PageEntity $pageEntity): PageEntity;

    public function getById(int $id): PageEntity | null;

    public function getByTaskId(int $taskId, string $status): array | null;

    public function update(PageEntity $pageEntity): PageEntity;
}
