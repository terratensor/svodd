<?php

declare(strict_types=1);

namespace App\Svodd\Service;

use App\Question\Entity\Question\CommentReadModel;
use App\Svodd\Entity\Chart\SvoddChartRepository;

class ChartDatetimeSetter
{
    private SvoddChartRepository $svoddChartRepository;
    private CommentReadModel $commentReadModel;

    public function __construct(
        SvoddChartRepository $svoddChartRepository,
        CommentReadModel $commentReadModel
    ) {
        $this->svoddChartRepository = $svoddChartRepository;
        $this->commentReadModel = $commentReadModel;
    }

    public function handle(): void
    {
        $entries = $this->svoddChartRepository->findAll();
        foreach ($entries as $data) {
            $startComment = $this->commentReadModel->findByDataId($data->start_comment_data_id);
            $data->start_datetime = $startComment->datetime->format('Y-m-d H:i:s');
            $endComment = $this->commentReadModel->findByDataId($data->end_comment_data_id);
            $data->end_datetime = $endComment->datetime->format('Y-m-d H:i:s');
            $this->svoddChartRepository->save($data);
        }
    }
}
