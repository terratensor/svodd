<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var QuestionView $question */

/** @var Pagination $pages */

use App\models\QuestionView;
use frontend\widgets\question\CommentSummary;
use yii\bootstrap5\LinkPager;
use yii\data\Pagination;

$this->title = 'Просмотр вопроса';
$this->params['breadcrumbs'][] = $this->title;

$page = Yii::$app->request->get()['page'] ?? 1;

$show = $page <= 1;

$js = <<<JS
  const bsCollapse = new bootstrap.Collapse('#collapseQuestion', {
  show: $show
})
JS;

$this->registerJs($js);

?>
<div class="site-index">

  <div class="row">
    <div class="col-md-12">

      <a class="btn btn-primary mb-3" data-bs-toggle="collapse" href="#collapseQuestion" role="button"
         aria-controls="collapseQuestion">
        Просмотр вопроса
      </a>
        <?php echo $this->render('question_card', ['question' => $question]); ?>
        <?php if ($page && $page <= 1): ?>
            <?php if ($question->linkedQuestions->getTotal() > 0) : ?>
            <p><strong>Связанных вопросов: <?= $question->linkedQuestions->getTotal(); ?></strong></p>
                <?php echo $this->render('question_linked', ['question' => $question]); ?>
            <?php endif; ?>
        <?php endif; ?>


        <?php
        // Property totalCount пусто пока не вызваны данные модели getModels(),
        // сначала получаем массив моделей, потом получаем общее их количество
        $comments = $question->provider->getModels();
        $pagination = new Pagination(
            [
                'totalCount' => $question->provider->getTotalCount(),
                'defaultPageSize' => Yii::$app->params['questions']['pageSize'],
            ]
        );
        ?>
        <?= CommentSummary::widget(['pagination' => $pagination]); ?>
        <?php echo $this->render('question_comments', ['comments' => $comments]); ?>

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
                    ]);
                ?>
            </div>
        </div>
    </div>
  </div>
</div>


