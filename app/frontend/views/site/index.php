<?php

/** @var yii\web\View $this */
/** @var ResultSet $results */
/** @var int $pages */
/** @var SearchForm $model */

use App\forms\SearchForm;
use Manticoresearch\ResultSet;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\LinkPager;

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <?php $form = ActiveForm::begin(
        [
            'method' => 'GET',
            'action' => ['site/index']
        ]
    ); ?>
  <div class="d-flex align-items-center">
      <?= $form->field($model, 'query', [
          'options' => [
              'class' => 'w-100 me-3', 'role' => 'search'
          ]
      ])->textInput(['class' => 'form-control'])->label(false); ?>
  </div>
    <?= Html::submitButton('Поиск', ['btn btn-primary']); ?>
    <?php ActiveForm::end(); ?>
    <?php if ($results): ?>
  <div class="row">
    <div class="col-md-12">
      <strong>Всего результатов: <?= $results->getTotal(); ?></strong>
        <?php foreach ($results as $hit): ?>
          <div class="card mb-4">
            <div class="card-body">
              <div class="card-title">
                  <?= $hit->get('data_id'); ?>
              </div>
                <?php foreach ($hit->getHighlight() as $field => $snippets): ?>

                  <div class="card-text">
                    <p><?= $hit->getData()['datetime']; ?>, <?= $hit->getData()['username']; ?></p>
                      <?php echo "Highlight for " . $field . ":\r\n\n";
                      foreach ($snippets as $snippet) {
                          echo "<blockquote>- " . $snippet . "</blockquote>\n";
                      } ?>
                    <p> <?php echo Yii::$app->formatter->asRaw($hit->getData()['text']); ?></p>
                  </div>
                <?php endforeach; ?>
            </div>
          </div>
        <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>
