<?php

declare(strict_types = 1);

namespace App\Domains\SharedKernel\Events\Dispatcher;

interface IEventDispatcher
{
    public function dispatch(object $event): void;
}
