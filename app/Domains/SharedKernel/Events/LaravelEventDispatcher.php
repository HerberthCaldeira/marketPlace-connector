<?php

declare(strict_types = 1);

namespace App\Domains\SharedKernel\Events;

use App\Domains\SharedKernel\Events\Dispatcher\IEventDispatcher;

final class LaravelEventDispatcher implements IEventDispatcher
{
    public function dispatch(object $event): void
    {
        event($event);
    }
}
