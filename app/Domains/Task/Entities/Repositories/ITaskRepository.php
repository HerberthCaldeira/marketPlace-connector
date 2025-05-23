<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities\Repositories;

use App\Domains\Task\Entities\TaskEntity;

interface ITaskRepository
{
    public function create(TaskEntity $task): TaskEntity;
}
