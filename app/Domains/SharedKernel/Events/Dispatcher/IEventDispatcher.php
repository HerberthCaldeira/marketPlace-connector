<?php

namespace App\Domains\SharedKernel\Events\Dispatcher;

interface IEventDispatcher
{
    public function dispatch(object $event): void;
}