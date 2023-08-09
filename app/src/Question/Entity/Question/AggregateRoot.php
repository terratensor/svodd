<?php

declare(strict_types=1);

namespace App\Question\Entity\Question;

interface AggregateRoot
{
    /**
     * @return array
     */
    public function releaseEvents(): array;
}
