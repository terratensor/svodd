<?php

/** @var $this \yii\web\View */
/** @var $model \app\form\SearchForm */

use frontend\widgets\search\BadgeFilter;
use yii\helpers\Html;

?>
<?php $isBadgeDisabled = BadgeFilter::isDisabled($model->badge);
$disabledAttribute = $isBadgeDisabled ? 'disabled' : ''; ?>

<div class="d-flex align-items-center badge-filter">

    <?php $options = [
        'class' => $model->badge === 'svodd' ? 'badge badge-svodd' : trim("badge badge-default $disabledAttribute"),
        'title' => 'Фильтр по СВОДД не работает в режиме поиска но номерам записей',
    ];

    $onClick = [
        'onclick' => 'location.href = "' . \yii\helpers\Url::to(BadgeFilter::makeUrl($model->badge === 'svodd' ? 'all' : 'svodd')) . '";',
        'title' => $model->badge === 'svodd' ? 'Отключить фильтр по СВОДД'  : 'Включить фильтр по СВОДД',
    ];
    if (!$isBadgeDisabled) {
        $options = array_merge($options, $onClick);
    } ?>
    <?= Html::tag(
        'span',
        'СВОДД',
        $options,
    ) ?>
    <?php $options = [
        'class' => $model->badge === 'aq' ? 'badge badge-aq' :  trim("badge badge-default $disabledAttribute"),
        'title' => 'Фильтр по ВОПРОС-ОТВЕТ не работает в режиме поиска но номерам записей',
    ];

    $onClick = [
        'onclick' => 'location.href = "' . \yii\helpers\Url::to(BadgeFilter::makeUrl($model->badge === 'aq' ? 'all' : 'aq')) . '";',
        'title' => $model->badge === 'aq' ? 'Отключить фильтр по ВОПРОС-ОТВЕТ'  : 'Включить фильтр по ВОПРОС-ОТВЕТ',
    ];
    if (!$isBadgeDisabled) {
        $options = array_merge($options, $onClick);
    } ?>
    <?= Html::tag(
        'span',
        'ВОПРОС-ОТВЕТ',
        $options,
    ); ?>
    <?php $options = [
        'class' => $model->badge === 'comments' ? 'badge badge-comments' :  trim("badge badge-default $disabledAttribute"),
        'title' => 'Фильтр по КОММЕНТАРИИ не работает в режиме поиска но номерам записей',
    ];

    $onClick = [
        'onclick' => 'location.href = "' . \yii\helpers\Url::to(BadgeFilter::makeUrl($model->badge === 'comments' ? 'all' : 'comments')) . '";',
        'title' => $model->badge === 'aq' ? 'Отключить фильтр по КОММЕНТАРИИ'  : 'Включить фильтр по КОММЕНТАРИИ',
    ];
    if (!$isBadgeDisabled) {
        $options = array_merge($options, $onClick);
    } ?>
    <?= Html::tag(
        'span',
        'КОММЕНТАРИИ',
        $options,
    ) ?>
</div>