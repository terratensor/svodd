<?php

use yii\helpers\Html;

/** @var \yii\web\View $this */
/** @var app\Bookmark\Entity\Comment\Bookmark $bookmark */

if ($bookmark) {
    echo Html::a('<i class="bi bi-bookmark-fill"></i>',[
        'bookmark/add', 'id' => $model->data_id
    ]);
}
else {

    echo Html::a('<i class="bi bi-bookmark"></i>', [
        'bookmark/add', 'id' => $model->data_id
    ]);
}
