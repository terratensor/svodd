<?php

/** @var $this \yii\web\View */
/** @var $model \app\form\SearchForm */

use frontend\widgets\search\BadgeFilter;
use yii\helpers\Html;

?>

<div class="d-flex align-items-center badge-filter">
    <?= Html::tag(
        'span',
        'СВОДД',
        [
            'class' => $model->badge === 'svodd' ? 'badge badge-svodd' : 'badge badge-default',
            'onclick' => 'location.href = "' . \yii\helpers\Url::to(BadgeFilter::makeUrl($model->badge === 'svodd' ? 'all' : 'svodd')) . '";'
        ]
    ) ?>
    <?= Html::tag(
        'span',
        'ВОПРОС-ОТВЕТ',
        [
            'class' => $model->badge === 'aq' ? 'badge badge-aq' : 'badge badge-default',
            'onclick' => 'location.href = "' . \yii\helpers\Url::to(BadgeFilter::makeUrl($model->badge === 'aq' ? 'all' : 'aq')) . '";'
        ]
    ) ?>
    <?= Html::tag(
        'span',
        'КОММЕНТАРИИ',
        [
            'class' => $model->badge === 'comments' ? 'badge badge-comments' : 'badge badge-default',
            'onclick' => 'location.href = "' . \yii\helpers\Url::to(BadgeFilter::makeUrl($model->badge === 'comments' ? 'all' : 'comments')) . '";'
        ]
    ) ?>
</div>