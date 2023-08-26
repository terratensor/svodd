<?php

declare(strict_types=1);

namespace App\dispatchers;

interface AppEventDispatcher
{
    public function dispatchAll(array $events): void;
    public function dispatch($event): void;
}
