<?php

/** @var ActiveDataProvider $dataProvider */
/** @var View $this */

use App\Question\Entity\Question\Comment;
use frontend\widgets\Scroll\ScrollWidget;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\web\View;


$this->title = 'Большая СВОДДная тема';

$this->params['meta_description'] = '24 февраля 2022 года президент России Владимир Путин в ответ на обращение руководителей республик Донбасса принял решение о проведении СВОДД. 3 октября ЛНР, ДНР, Херсонская и Запорожская области стали частью России. Сообщество ведёт соборное обсуждение глобальной специальной военной операции денацификации и демилитаризации.';

$position = 1;
$pagination = new Pagination(
    [
        'totalCount' => $dataProvider->getTotalCount(),
        'defaultPageSize' => Yii::$app->params['questions']['pageSize'],
    ]
);

$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('comment_summary', ['pagination' => $pagination]); ?>

<?php /** @var Comment $model */
foreach ($dataProvider->getModels() as $model): ?>

    <?php if ($model->position === 1 && $dataProvider->sort->attributeOrders['date'] === SORT_ASC): ?>
        <?= $this->render('question', ['question' => $model->question, 'dataProvider' => $dataProvider]); ?>
    <?php endif; ?>

    <?= $this->render('comment', ['model' => $model]); ?>
    <?php $position = $model->data_id; ?>

    <?php if ($model->position === 1 && $dataProvider->sort->attributeOrders['date'] === SORT_DESC): ?>
        <?= $this->render('question', ['question' => $model->question, 'dataProvider' => $dataProvider]); ?>
    <?php endif; ?>

<?php endforeach; ?>

<div class="container container-pagination">
    <div class="detachable">
        <?= LinkPager::widget(
            [
                'pagination' => $pagination,
                'firstPageLabel' => true,
                'lastPageLabel' => true,
                'maxButtonCount' => 3,
                'options' => [
                    'class' => 'd-flex justify-content-center'
                ],
                'listOptions' => ['class' => 'pagination mb-0']
            ]
        );
        ?>
    </div>
</div>

<?= ScrollWidget::widget(['data_entity_id' => $model->data_id ?? 0]); ?>