<?php

declare(strict_types=1);

/** @var ActiveDataProvider $dataProvider */

use App\models\QuestionStats;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\helpers\Url;

$pagination = new Pagination(
    [
        'totalCount' => $dataProvider->getTotalCount(),
        'defaultPageSize' => Yii::$app->params['questions']['pageSize'],
    ]
);

$this->title = 'Архив вопросов';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
  <div class="d-flex align-items-start ">
    <label aria-label="Сортировка" for="input-sort"></label>
    <select id="input-sort" class="form-select mb-3" onchange="location = this.value;">
        <?php
        $values = [
            '' => 'По дате вопроса',
            '-comments_count' => 'По количеству комментариев',
            '-last_comment_date' => 'По дате последнего комментария',
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
<div class="list-group mb-4">
    <?php /** @var QuestionStats $model */
    foreach ($dataProvider->getModels() as $model): ?>

      <a href="<?= Url::to(['question/view', 'id' => $model->question_id, 'page' => 1]); ?>"
         class="list-group-item list-group-item-action" aria-current="true">
        <div class="d-flex w-100 justify-content-between">
          <h5 class="mb-1"><?= $model->questionDate ? $model->getQuestionDate()->format('d.m.Y') : 'Дата не установлена'; ?></h5>
          <small>Комментариев: <?= $model->comments_count; ?></small>
        </div>
        <p class="mb-1"><?= $model->url; ?></p>
        <small>Последний
          комментарий <?= $model->lastCommentDate ? $model->getLastCommentDate()->format('d.m.Y H:i') : ': дата не установлена' ?></small>
      </a>

    <?php endforeach; ?>
</div>

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
