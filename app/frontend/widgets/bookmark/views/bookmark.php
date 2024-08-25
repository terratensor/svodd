<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var \yii\web\View $this */
/** @var app\Bookmark\Entity\Comment\Bookmark $bookmark */

if (isset($bookmark) && $bookmark) {
    echo Html::a(
        Html::tag('i', '', [
            'class' => 'bi bi-bookmark-fill',
            'data-bs-toggle' => 'tooltip',
            'data-bs-placement' => 'bottom',
            'data-bs-title' => 'Убрать из закладок',
            'id' => 'bookmark-' . $model->data_id,
            'data-href' => Url::to(
                [
                    'bookmark/index',
                    'id' => $model->data_id
                ]
            ),
            'data-method' => 'post',
        ]),
        false,
        [
            'class' => 'bookmarks',
            'rel' => 'nofollow',
        ]
    );
} else {

    echo Html::a(
        Html::tag('i', '', [
            'class' => 'bi bi-bookmark',
            'data-bs-toggle' => 'tooltip',
            'data-bs-placement' => 'bottom',
            'data-bs-title' => 'Добавить в закладки',
            'id' => 'bookmark-' . $model->data_id,
            'data-href' => Url::to([
                'bookmark/index',
                'id' => $model->data_id
            ]),
            'data-method' => 'post',
        ]),
        false,
        [
            'class' => 'bookmarks',
            'rel' => 'nofollow',
        ]
    );
}
