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
        return Html::tag(
                'div',
                $this->model->datetime->format('H:i d.m.Y') . ", " .
                Html::tag(
                    'span',
                    $this->model->username,
                    ['class' => 'username']
                )
            ) .
            Html::tag('div',

                      BookmarkWidget::widget(['model' => $this->model], [
                          'bookmark/add', 'id' => $this->model->data_id
                      ],
                              [
                                  'class' => 'text-decoration-none', 'data-method' => 'post',
                                  'data-pjax' => '0'
                              ]
                      ) .
                      Html::tag('div', "#" . $this->model->data_id)
                ,     ['class' => 'd-flex justify-content-between']
            );
    }
}
