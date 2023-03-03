<?php

/** @var yii\web\View $this */
/** @var QuestionDataProvider $results */
/** @var QuestionStats[] $list */
/** @var Pagination $pages */

/** @var SearchForm $model */

use App\forms\SearchForm;
use App\helpers\DateHelper;
use App\models\Comment;
use App\models\QuestionStats;
use App\repositories\Question\QuestionDataProvider;
use frontend\widgets\question\CommentSummary;
use frontend\widgets\question\SvoddListWidget;
use frontend\widgets\search\SearchResultSummary;
use frontend\widgets\search\FollowQuestion;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\data\Pagination;
use yii\helpers\Url;

$this->title = 'ФКТ поиск';

?>
<div class="site-index">
    <?php if (!$results): ?>
      <h4>Хронология обсуждений событий с начала СВОДД:</h4>
    <?php endif; ?>

    <?php $form = ActiveForm::begin(
        [
            'method' => 'GET',
            'action' => ['site/index'],
            'options' => ['class' => 'mt-3'],
        ]
    ); ?>
  <div class="d-flex align-items-center">
      <?= $form->field($model, 'query', [
          'inputTemplate' => '<div class="input-group mb-3">
          {input}
          <button class="btn btn-outline-primary" type="submit" id="button-addon2">Поиск</button></div>',
          'options' => [
              'class' => 'w-100', 'role' => 'search'
          ]
      ])->textInput(
          [
              'class' => 'form-control form-control-lg',
              'placeholder' => "Поиск"
          ]
      )->label(false); ?>
  </div>
    <?php ActiveForm::end(); ?>
    <?php if ($results): ?>
    <?php
    // Property totalCount пусто пока не вызваны данные модели getModels(),
    // сначала получаем массив моделей, потом получаем общее их количество
    /** @var Comment[] $comments */
    $comments = $results->getModels();
    $pagination = new Pagination(
        [
            'totalCount' => $results->getTotalCount(),
            'defaultPageSize' => Yii::$app->params['questions']['pageSize'],
        ]
    );
    ?>
  <div class="row">
    <div class="col-md-12">
      <p><strong>Всего результатов: <?= $results->getTotalCount(); ?></strong></p>
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
                    'datetime' => 'Сначала старые комментарии',
                    '-datetime' => 'Сначала новые комментарии',
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
        <?php foreach ($comments as $comment): ?>
          <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
              <div><?= DateHelper::showDateFromTimestamp($comment->datetime); ?>, <?= $comment->username; ?></div>
              <div><?= "#" . $comment->data_id; ?></div>
            </div>
            <div class="card-body">
                <?php foreach ($comment->highlight as $field => $snippets): ?>
                  <div class="card-text comment-text">
                      <?php foreach ($snippets as $snippet): ?>
                          <?php echo Yii::$app->formatter->asRaw(htmlspecialchars_decode($snippet)); ?>
                      <?php endforeach; ?>
                  </div>
                <?php endforeach; ?>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <?= FollowQuestion::widget(['comment' => $comment, 'pagination' => $pagination]); ?>
                <?php $link = "https://фкт-алтай.рф/qa/question/view-" . $comment->parent_id; ?>
                <?= Html::a(
                    $link,
                    $link . "#:~:text=" . $comment->datetime,
                    ['target' => '_blank']
                ); ?>
            </div>
          </div>
        <?php endforeach; ?>
      <div class="fixed-bottom">
        <div class="container">
            <?php echo LinkPager::widget(
                [
                    'pagination' => $pagination,
                    'firstPageLabel' => true,
                    'lastPageLabel' => true,
                    'maxButtonCount' => 5,
                    'options' => [
                        'class' => 'd-flex justify-content-center'
                    ]
                ]
            ); ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php else: ?>

    <?php echo SvoddListWidget::widget(['models' => $list]); ?>

<?php endif; ?>
