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
<?php
/** @var Comment $model */
foreach ($dataProvider->getModels() as $model): ?>
  <p>
      <?php echo $model->data_id; ?>
      <?php echo $model->text; ?>
  </p>
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
