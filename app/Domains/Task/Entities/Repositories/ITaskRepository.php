<?php 

namespace App\Domains\Task\Entities\Contracts\Repositories;

use App\Domains\Task\Entities\TaskEntity;

interface ITaskRepository
{
    public function create(array $data): TaskEntity;    
}
