<?php

namespace frontend\controllers;

use App\Question\Entity\Question\CommentReadModel;
use App\Question\Entity\Statistic\QuestionStatsReadModel;
use App\Question\Entity\Statistic\QuestionStatsRepository;
use yii\web\Controller;

class SvoddController extends Controller
{
    private QuestionStatsRepository $questionStatsRepository;
    private CommentReadModel $commentReadModel;

    public function __construct(
        $id,
        $module,
        QuestionStatsRepository $questionStatsRepository,
        CommentReadModel $commentReadModel,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->questionStatsRepository = $questionStatsRepository;
        $this->commentReadModel = $commentReadModel;
    }

    public function actionIndex(): string
    {
        $list = $this->questionStatsRepository->findAllForList();
        return $this->render('index', ['list' => $list]);
    }

    public function actionView(): string
    {
        $dataProvider = $this->commentReadModel->findBySvoddQuestions();
        return $this->render('view', ['dataProvider' => $dataProvider]);
    }
}
