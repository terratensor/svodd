<?php

/** @var ActiveDataProvider $dataProvider */

use App\Question\Entity\Question\Comment;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;


$pagination = new Pagination(
    [
        'totalCount' => $dataProvider->getTotalCount(),
        'defaultPageSize' => Yii::$app->params['questions']['pageSize'],
    ]
);

$this->title = 'Большая СВОДДная тема';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('comment_summary', ['pagination' => $pagination]); ?>

<?php /** @var Comment $model */
foreach ($dataProvider->getModels() as $model): ?>

    <?php if ($model->position === 1 && $dataProvider->sort->attributeOrders['date'] === SORT_ASC): ?>
        <?= $this->render('question', ['question' => $model->question, 'dataProvider' => $dataProvider]); ?>
    <?php endif; ?>

    <?= $this->render('comment', ['model' => $model]); ?>

    <?php if ($model->position === 1 && $dataProvider->sort->attributeOrders['date'] === SORT_DESC): ?>
        <?= $this->render('question', ['question' => $model->question, 'dataProvider' => $dataProvider]); ?>
    <?php endif; ?>

<?php endforeach; ?>

<div class="container container-pagination">
  <div class="detachable fixed-bottom">
      <?= LinkPager::widget(
          [
              'pagination' => $pagination,
              'firstPageLabel' => true,
              'lastPageLabel' => true,
              'maxButtonCount' => 5,
              'options' => [
                  'class' => 'd-flex justify-content-center'
              ],
              'listOptions' => ['class' => 'pagination mb-0']
          ]
      );
      ?>
  </div>
</div>
