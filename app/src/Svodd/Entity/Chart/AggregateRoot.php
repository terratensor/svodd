<?php

declare(strict_types=1);

namespace App\Svodd\Entity\Chart;

interface AggregateRoot
{
    /**
     * @return array
     */
    public function releaseEvents(): array;
}
