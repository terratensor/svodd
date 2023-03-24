<?php

namespace frontend\controllers;

use App\repositories\Question\QuestionStatsRepository;
use yii\web\Controller;

class SvoddController extends Controller
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

    public function actionIndex(): string
    {
        $list = $this->questionStatsRepository->findAllForList();
        return $this->render('index', ['list' => $list]);
    }

    public function actionView(): string
    {
        $list = $this->questionStatsRepository->findAllForList();
        return $this->render('index', ['list' => $list]);
    }
}
