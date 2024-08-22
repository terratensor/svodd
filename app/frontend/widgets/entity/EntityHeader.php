<?php

declare(strict_types=1);

namespace frontend\widgets\entity;


use App\Question\Entity\Question\Comment;
use frontend\widgets\bookmark\BookmarkWidget;
use yii\base\Widget;
use yii\helpers\Html;

class EntityHeader extends Widget
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
        );
    }
}