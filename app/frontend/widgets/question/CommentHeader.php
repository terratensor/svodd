<?php

declare(strict_types=1);

namespace frontend\widgets\question;


use App\Question\Entity\Question\Comment;
use yii\base\Widget;
use yii\helpers\Html;

class CommentHeader extends Widget
{
    public Comment $model;

    public function run(): string
    {
        return Html::tag(
                'div',
                $this->model->datetime->format('H:i d.m.Y') . ", " .
                Html::tag(
                    'span',
                    $this->model->username,
                    ['class' => 'username']
                )
            ) .
            Html::tag('div', "#" . $this->model->data_id);
    }
}
