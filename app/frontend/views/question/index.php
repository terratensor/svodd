<?php

declare(strict_types=1);

/** @var ActiveDataProvider $dataProvider */

use App\models\QuestionStats;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\Html;
use yii\widgets\ListView;


$pagination = new Pagination(
    [
        'totalCount' => $dataProvider->getTotalCount(),
        'defaultPageSize' => Yii::$app->params['questions']['pageSize'],
    ]
);

?>
<div class="list-group">
    <?php /** @var QuestionStats $model */
foreach ($dataProvider->getModels() as $model): ?>
  <?php

  $title = $model->title;
  if ($title === '') {
      $title = 'Текущая активная тема';
  }
  if ($model->number === null && $model->title === null) {
      $item2 = Html::tag('h5', $model->getDate()->format('H:i d.m.Y')) . $model->url;
  } else {
      $item2 = Html::tag('h5', $model->number . '. ' . $title) . $model->url;
  }
  $item1 = Html::tag('div', $item2, ['class' => 'ms-2 me-auto']) .
      Html::tag('span', $model->comments_count, ['class' => 'badge bg-primary rounded-pill']);
  $item = Html::tag('div', $item1, ['class' => 'd-flex w-100 justify-content-between align-items-start']);
  $link = Html::a($item, ['question/view', 'id' => $model->question_id, 'page' => 1], ['class' => 'list-group-item list-group-item-action']);

  echo $link;
  ?>
  <?php endforeach; ?>
</div>

<div class="fixed-bottom">
  <div class="container">
      <?= LinkPager::widget(
          [
              'pagination' => $pagination,
              'firstPageLabel' => true,
              'lastPageLabel' => true,
              'maxButtonCount' => 5,
              'options' => [
                  'class' => 'd-flex justify-content-center'
              ]
          ]
      );
      ?>
  </div>
</div>
