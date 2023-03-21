<?php

declare(strict_types=1);

use App\Feedback\Form\SendMessage\FeedbackForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;

/** @var ActiveDataProvider $dataProvider */
/** @var FeedbackForm $model */

?>
<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'text')->textarea(['rows' => 5]) ?>

  <div class="form-group">
      <?= Html::submitButton('Отправить сообщение', ['class' => 'btn btn-primary btn-lg btn-flat']) ?>
  </div>

<?php ActiveForm::end(); ?>
