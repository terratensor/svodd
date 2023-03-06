<?php

declare(strict_types=1);

/** @var ActiveDataProvider $dataProvider */

/** @var Question $question */

use App\Question\Entity\Question\Question;
use frontend\widgets\question\CommentSummary;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\Url;

$position = Yii::$app->request->get()['c'] ?? 0;

$pagination = new Pagination(
    [
        'totalCount' => $dataProvider->getTotalCount(),
        'defaultPageSize' => Yii::$app->params['questions']['pageSize'],
    ]
);


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
        <?php echo $this->render('question', ['question' => $question]); ?>

        <?php if ($page && $page <= 1): ?>
            <?php if (count($question->linkedQuestions) > 0) : ?>
            <p><strong>Связанных вопросов: <?= count($question->linkedQuestions); ?></strong></p>
                <?php echo $this->render('linked_questions', ['question' => $question]); ?>
            <?php endif; ?>
        <?php endif; ?>

      <div class="row">
        <div class="col-md-8 d-flex align-items-center">
            <?= CommentSummary::widget(['pagination' => $pagination]); ?>
        </div>
        <div class="col-md-4">
          <div class="d-flex align-items-start ">
            <label aria-label="Сортировка" for="input-sort"></label>
            <select id="input-sort" class="form-select mb-3" onchange="location = this.value;">
                <?php
                $values = [
                    '' => 'Сортировка по умолчанию',
                    '-date' => 'Сначала новые комментарии',
                    'date' => 'Сначала старые комментарии',
                ];
                $current = Yii::$app->request->get('sort');
                ?>
                <?php foreach ($values as $value => $label): ?>
                  <option value="<?= Html::encode(Url::current(['sort' => $value ?: null])) ?>"
                          <?php if ($current == $value): ?>selected="selected"<?php endif; ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
          </div>
        </div>
      </div>

        <?php echo $this->render('question_comments', ['dataProvider' => $dataProvider]); ?>

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
    </div>
  </div>
</div>
