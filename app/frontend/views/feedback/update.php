<?php

declare(strict_types=1);

/** @var FeedbackForm $model */

/** @var Feedback $feedback */

use App\Feedback\Entity\Feedback\Feedback;
use App\Feedback\Form\SendMessage\FeedbackForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Редактирование сообщения';
$this->params['breadcrumbs'][] = ['label' => 'Обратная связь', 'url' => ['feedback/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-update-message">
  <h3><?= Html::encode($this->title) ?></h3>
  <div class="row">
    <div class="col-sm-12 mb-3 mb-sm-4">
        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'text')->textarea(['rows' => 5])->label(false) ?>

      <div class="form-group">
          <?= Html::submitButton('Сохранить', [
              'class' => 'btn btn-primary btn-lg btn-flat'
          ]) ?>
          <?= Html::a('Отмена', ['feedback/index', '#' => 'comment-'.$feedback->getId()], [
              'class' => 'btn btn-light btn-lg btn-flat'
          ]) ?>
      </div>

        <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
