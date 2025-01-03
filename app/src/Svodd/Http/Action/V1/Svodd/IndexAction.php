<?php

namespace App\Svodd\Http\Action\V1\Svodd;

use App\Question\Entity\Question\CommentRepository;
use App\Svodd\Entity\Chart\SvoddChartRepository;
use yii\base\Action;

class IndexAction extends Action
{
    private SvoddChartRepository $svoddChartRepository;
    private CommentRepository $commentRepository;

    public function __construct(
        $id,
        $module,
        SvoddChartRepository $svoddChartRepository,
        CommentRepository $commentRepository,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->svoddChartRepository = $svoddChartRepository;
        $this->commentRepository = $commentRepository;
    }

    public function run(): string
    {
        $this->controller->layout = 'svodd';
        $data = $this->svoddChartRepository->findAllDesc();

        return $this->controller->render('index', [
            'data' => $data,
            'last_comment' => $this->commentRepository->findLastComment(),
        ]);
    }
}
