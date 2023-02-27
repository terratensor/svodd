<?php

/** @var yii\web\View $this */
/** @var ResultSet $results */
/** @var Pagination $pages */

/** @var SearchForm $model */

use App\forms\SearchForm;
use Manticoresearch\ResultSet;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;
use yii\data\Pagination;

$this->title = 'ФКТ поиск';

?>
<div class="site-index">
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
              'class' => 'w-100 me-3', 'role' => 'search'
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
  <div class="row">
    <div class="col-md-12">
      <p><strong>Всего результатов: <?= $results->getTotal(); ?></strong></p>
        <?php foreach ($results as $hit): ?>
          <div class="card mb-4">
            <div class="card-header d-flex justify-content-between">
              <div><?= $hit->getData()['datetime']; ?>, <?= $hit->getData()['username']; ?></div>
              <div><?= "#" . $hit->get('data_id'); ?></div>
            </div>
            <div class="card-body">
                <?php foreach ($hit->getHighlight() as $field => $snippets): ?>
                  <div class="card-text comment-text">
                      <?php foreach ($snippets as $snippet): ?>
                          <?php echo $snippet . "\n"; ?>
                      <?php endforeach; ?>
                    <p> <?php //echo Yii::$app->formatter->asRaw($hit->getData()['text']); ?></p>
                  </div>
                <?php endforeach; ?>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <?= Html::a(
                    'Перейти к вопросу',
                    ['site/question', 'id' => $hit->get('parent_id'), 'position' => $hit->get('position')]
                ); ?>
                <?php $link = "https://фкт-алтай.рф/qa/question/view-" . $hit->get('parent_id'); ?>
                <?= Html::a(
                    $link,
                    $link . "#:~:text=" . $hit->getData()['datetime'],
                    ['target' => '_blank']
                ); ?>
            </div>
          </div>
        <?php endforeach; ?>

        <?php
        echo LinkPager::widget(
            [
                'pagination' => new Pagination(['totalCount' => $results->getTotal()]),
            ]);
        ?>
    </div>
  </div>
</div>
<?php endif; ?>
