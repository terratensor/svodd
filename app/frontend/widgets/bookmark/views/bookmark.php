<?php

use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var app\Bookmark\Entity\Comment\Bookmark $bookmark */

if (isset($bookmark) && $bookmark) {
    echo Html::a(
        Html::tag('i', '', [
            'class' => 'bi bi-bookmark-fill',
            'data-bs-toggle' => 'tooltip',
            'data-bs-placement' => 'bottom',
            'data-bs-title' => 'Убрать из закладок',
        ]),
        [
            'bookmark/index',
            'id' => $model->data_id
        ],
        [
            'class' => 'bookmarks',
        ]
    );
} else {

    echo Html::a(
        Html::tag('i', '', [
            'class' => 'bi bi-bookmark',
            'data-bs-toggle' => 'tooltip',
            'data-bs-placement' => 'bottom',
            'data-bs-title' => 'Добавить в закладки',
        ]),
        [
            'bookmark/index',
            'id' => $model->data_id
        ],
        [
            'class' => 'bookmarks',
        ]
    );
}
