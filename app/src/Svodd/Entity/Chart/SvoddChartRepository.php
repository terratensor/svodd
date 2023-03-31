<?php

declare(strict_types=1);

namespace App\Svodd\Entity\Chart;

class SvoddChartRepository
{
    public function findAll(): array
    {
        return Data::find()->all();
    }
}
