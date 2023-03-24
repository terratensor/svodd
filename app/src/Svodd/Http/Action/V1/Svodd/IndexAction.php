<?php

namespace App\Svodd\Http\Action\V1\Svodd;

use App\Question\Entity\Statistic\QuestionStatsRepository;
use yii\base\Action;

class IndexAction extends Action
{
    private QuestionStatsRepository $questionStatsRepository;

    public function __construct(
        $id,
        $module,
        QuestionStatsRepository $questionStatsRepository,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->questionStatsRepository = $questionStatsRepository;
    }

    public function run(): string
    {
        $list = $this->questionStatsRepository->findAllForList();
        return $this->controller->render('index', ['list' => $list]);
    }
}
