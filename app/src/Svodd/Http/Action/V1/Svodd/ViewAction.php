<?php

namespace App\Svodd\Http\Action\V1\Svodd;

use App\Question\Entity\Question\CommentReadModel;
use yii\base\Action;

class ViewAction extends Action
{
    private CommentReadModel $commentReadModel;

    public function __construct(
        $id,
        $module,
        CommentReadModel $commentReadModel,
        $config = []
    )
    {
        parent::__construct($id, $module, $config);
        $this->commentReadModel = $commentReadModel;
    }

    public function run(): string
    {
        $dataProvider = $this->commentReadModel->findBySvoddQuestions();
        return $this->controller->render('view', ['dataProvider' => $dataProvider]);
    }
}
