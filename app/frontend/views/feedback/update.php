<?php

declare(strict_types=1);

/** @var FeedbackForm $model */

/** @var Feedback $feedback */

use App\Feedback\Entity\Feedback\Feedback;
use App\Feedback\Form\SendMessage\FeedbackForm;
use yii\bootstrap5\Html;
use yii\widgets\ActiveForm;

?>
<div class="row">
  <div class="col-sm-12 mb-3 mb-sm-4">
      <?php $form = ActiveForm::begin(); ?>

      <?= $form->field($model, 'text')->textarea(['rows' => 5])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить сообщение', [
            'class' => 'btn btn-primary btn-lg btn-flat'
        ]) ?>
    </div>

      <?php ActiveForm::end(); ?>
  </div>
</div>
