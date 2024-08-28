<?php

declare(strict_types=1);

/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var App\Question\Entity\Question\Question $question */

use frontend\widgets\question\CommentSummary;
use frontend\widgets\question\MetaQuestion;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\data\Pagination;
use yii\helpers\Url;

$position = Yii::$app->request->get()['c'] ?? 0;

$pagination = new Pagination(
  [
    'totalCount' => $dataProvider->getTotalCount(),
    'defaultPageSize' => Yii::$app->params['questions']['pageSize'],
  ]
);


$this->title = 'Просмотр вопроса #' . $question->data_id;
$this->params['meta_description'] = MetaQuestion::widget(['question' => $question]);

$this->params['breadcrumbs'][] = ['label' => 'Архив вопросов', 'url' => ['question/index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->urlManager->createAbsoluteUrl(['question/view', 'id' => $question->data_id])]);
?>
<div class="site-index">
  <div class="row">
    <div class="col-md-12">

      <?php if ($pagination->getPage() < 1): ?>
        <?php echo $this->render('question', ['question' => $question]); ?>
      <?php endif; ?>

      <?php if ($pagination->getPage() < 1): ?>
        <?php if (count($question->linkedQuestions) > 0) : ?>
          <p><strong>Связанных вопросов: <?= count($question->linkedQuestions); ?></strong></p>
          <?php echo $this->render('linked_questions', ['question' => $question]); ?>
        <?php endif; ?>
      <?php endif; ?>

      <?php if ($dataProvider->getCount() > 0) : ?>
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
                  '' => 'Сначала старые комментарии',
                  '-date' => 'Сначала новые комментарии',
                ];
                $current = Yii::$app->request->get('sort');
                ?>
                <?php foreach ($values as $value => $label): ?>
                  <option value="<?= Html::encode(Url::current(['sort' => $value ?: null])) ?>"
                    <?php if ($current == $value): ?>selected="selected" <?php endif; ?>><?= $label ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <?php echo $this->render('question_comments', ['dataProvider' => $dataProvider]); ?>

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
    </div>
  </div>
</div>