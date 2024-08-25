<?php

declare(strict_types=1);

namespace frontend\widgets\question;


use App\Question\Entity\Question\Comment;
use frontend\widgets\bookmark\BookmarkWidget;
use yii\base\Widget;
use yii\helpers\Html;

class CommentHeader extends Widget
{
    public Comment $model;

    public function run(): string
    {
        $time = Html::tag(
            'time',
            $this->model->datetime->format('H:i d.m.Y') . ", ",
            ['datetime' => $this->model->datetime->format('Y-m-d H:i:s')],
        );
        return Html::tag(
            'div',
            $time .
                Html::tag(
                    'span',
                    $this->model->username,
                    ['class' => 'username']
                )
        ) .
            Html::tag(
                'div',
                Html::tag('div', "#" . $this->model->data_id),
                ['class' => 'd-flex justify-content-between']
            );
    }
}
