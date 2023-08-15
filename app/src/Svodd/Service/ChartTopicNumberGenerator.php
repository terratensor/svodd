<?php

declare(strict_types=1);

namespace App\Svodd\Service;

use App\Svodd\Entity\Chart\SvoddChartRepository;

class ChartTopicNumberGenerator
{
    private SvoddChartRepository $svoddChartRepository;

    public function __construct(SvoddChartRepository $svoddChartRepository) {
        $this->svoddChartRepository = $svoddChartRepository;
    }

    public function generate(): int
    {
        $data = $this->svoddChartRepository->findCurrent();
        return $data->topic_number + 1;
    }
}
