<?php

namespace frontend\widgets\question\card;

use App\models\Comment;
use yii\base\Widget;
use yii\helpers\Html;

class Header extends Widget
{
    public Comment $model;

    public function run(): string
    {
        return Html::tag('div', $this->model->datetime . ", " .
            Html::tag('span', $this->model->username, ['class' => 'username'])
        ) .
        Html::tag('div', "#" . $this->model->data_id);
    }
}
