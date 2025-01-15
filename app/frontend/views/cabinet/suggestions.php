<?php

declare(strict_types=1);

use yii\grid\GridView;
use yii\data\Pagination;
use yii\bootstrap5\LinkPager;

$total_count = $dataProvider->indexed_documents !== 0 ? $dataProvider->indexed_documents : $dataProvider->getTotalCount();

$pagination = new Pagination(
    [
      'totalCount' => $total_count,
      'defaultPageSize' => Yii::$app->params['questions']['pageSize'],
    ]
  );

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'layout' => "{summary}\n{items}",
    'columns' => [
        'sid',
        'suggestion',
    ],
]);
?>
<div class="container container-pagination">
    <div class="detachable">
        <?php echo LinkPager::widget(
        [
            'pagination' => $pagination,
            'firstPageLabel' => true,
            'lastPageLabel' => true,
            'maxButtonCount' => 3,
            'options' => [
            'class' => 'd-flex justify-content-center'
            ],
            'listOptions' => ['class' => 'pagination mb-0'],
            'linkOptions' => ['class' => 'page-link', 'rel' => 'nofollow']
        ]
        ); ?>
    </div>
</div>