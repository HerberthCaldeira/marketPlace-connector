<?php

declare(strict_types = 1);

namespace App\Domains\Task\Entities\States\TaskPage;

interface ITaskPageState
{
    public function getStateName(): string;

    public function fetched(): void;

    public function pending(): void;

    public function failed(): void;
}
