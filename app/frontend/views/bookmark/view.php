<?php

declare(strict_types=1);

use yii\data\Pagination;
use yii\bootstrap5\LinkPager;
use frontend\widgets\Scroll\ScrollWidget;

/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\web\View $this */
/** @var App\Question\Entity\Question\Comment $model */


$this->title = 'Закладки';

$this->params['meta_description'] = 'Список закладок';
$this->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->urlManager->createAbsoluteUrl(['bookmark/view'])]);


$pagination = new Pagination(
    [
        'totalCount' => $dataProvider->getTotalCount(),
        'defaultPageSize' => Yii::$app->params['questions']['pageSize'],
    ]
);

echo $this->render('comment_summary', ['pagination' => $pagination]);

if ($dataProvider->getCount() === 0) {
    echo '<p>У вас ещё нет закладок.</p>';
    return;
}
foreach ($dataProvider->getModels() as $model) {    
    echo $this->render('comment', ['model' => $model->comment, 'pagination' => $pagination]);
}

?>
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

<?= ScrollWidget::widget(['data_entity_id' => $model->comment->data_id ?? 0]); ?>