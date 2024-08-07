<?php

declare(strict_types=1);

namespace App\Question\Entity\Question;

trait EventTrait
{
    private array $events = [];

    protected function recordEvent($event): void
    {
        $this->events[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->events;
        $this->events = [];
        return $events;
    }
}
