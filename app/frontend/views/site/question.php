<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var Question $question */

/** @var Pagination $pages */

use App\models\Question;
use yii\bootstrap5\LinkPager;
use yii\data\Pagination;

$this->title = 'Просмотр вопроса';
$this->params['breadcrumbs'][] = $this->title;

//var_dump($question->comments->getFacets());
$commentsCount = $question->comments->getFacets()['group_type']['buckets'][0]['doc_count'] ?? 0;
$linkedQuestionsCount = $question->linkedQuestions->getFacets()['group_type']['buckets'][0]['doc_count'] ?? 0;
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

          <a class="btn btn-primary mb-3" data-bs-toggle="collapse" href="#collapseQuestion" role="button" aria-controls="collapseQuestion">
            Просмотр вопроса
          </a>
            <?php echo $this->render('question_card', ['question' => $question]); ?>
        <?php if ($page && $page <= 1): ?>
            <?php if ($linkedQuestionsCount > 0) : ?>
                <?= "<p><strong>Связанных вопросов: $linkedQuestionsCount </strong></p>"; ?>
                <?php echo $this->render('question_linked', ['question' => $question]); ?>
            <?php endif; ?>
        <?php endif; ?>
      <p><strong>Всего комментариев: <?= $commentsCount; ?></strong></p>
        <?php echo $this->render('question_comments', ['question' => $question]); ?>

        <?php
        echo LinkPager::widget(
            [
                'pagination' => new Pagination(['totalCount' => $question->comments->getTotal()]),
                'firstPageLabel' => true,
                'lastPageLabel' => true,
                'maxButtonCount' => 8,
            ]);
        ?>
    </div>
  </div>
</div>


