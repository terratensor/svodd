<?php

use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var app\Bookmark\Entity\Comment\Bookmark $bookmark */

if (isset($bookmark) && $bookmark) {
    echo Html::a('<i class="bi bi-bookmark-fill"></i>', [
        'bookmark/index',
        'id' => $model->data_id
    ], [
        'class' => 'bookmarks'
    ]);
} else {

    echo Html::a('<i class="bi bi-bookmark"></i>', [
        'bookmark/index',
        'id' => $model->data_id
    ], [
        'class' => 'bookmarks'
    ]);
}
