<?php

declare(strict_types=1);

namespace App\Svodd\Service;

use App\Question\Entity\Statistic\QuestionStatsRepository;
use App\Svodd\Entity\Chart\SvoddChartRepository;

class ChartDataUpdater
{
    private QuestionStatsRepository $questionStatsRepository;
    private SvoddChartRepository $svoddChartRepository;

    public function __construct(
        QuestionStatsRepository $questionStatsRepository,
        SvoddChartRepository $svoddChartRepository,
    ) {
        $this->questionStatsRepository = $questionStatsRepository;
        $this->svoddChartRepository = $svoddChartRepository;
    }

    public function handle(int $question_id): void
    {
        $data = $this->svoddChartRepository->findByQuestionId($question_id);
        if ($data?->active) {
            $stats = $this->questionStatsRepository->getByQuestionId($question_id);
            $data->end_comment_data_id = $stats->last_comment_data_id;
            $data->comments_count = $stats->comments_count;
            $this->svoddChartRepository->save($data);
        }
    }
}
