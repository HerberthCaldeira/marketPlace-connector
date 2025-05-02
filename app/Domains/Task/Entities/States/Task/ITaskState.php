<?php

namespace App\Domains\Task\Entities\States\Task;

interface ITaskState
{
    public function getStateName(): string;
    public function started(): void;
    public function completed(): void;
    public function failed(): void;
}
