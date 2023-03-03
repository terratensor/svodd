<?php

namespace frontend\widgets\question\card;

use App\models\Comment;
use DateTimeImmutable;
use yii\base\Widget;
use yii\helpers\Html;

class Header extends Widget
{
    public Comment $model;

    public function run(): string
    {
        $date = new DateTimeImmutable();
        $date = $date->setTimeStamp($this->model->datetime);

        return Html::tag(
                'div',
                $date->format('H:i d.m.Y') . ", " .
                Html::tag(
                    'span',
                    $this->model->username,
                    ['class' => 'username']
                )
            ) .
            Html::tag('div', "#" . $this->model->data_id);
    }
}
