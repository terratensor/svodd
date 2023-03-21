<?php

declare(strict_types=1);

use App\Auth\Entity\User\Role;
use App\Feedback\Entity\Feedback\Feedback;
use App\Feedback\Form\SendMessage\FeedbackForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;

/** @var ActiveDataProvider $dataProvider */
/** @var FeedbackForm $model */
/** @var Feedback $feedback */

$disabled = 'disabled';
if (Yii::$app->user->can(Role::USER)) {
  $disabled = false;
}

$this->title = 'Отправить сообщение';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="feedback-messages">
  <h1><?= Html::encode($this->title) ?></h1>

  <p>
    Поделитесь своими мыслями, задайте вопрос, оставьте предложение или пожелание по развитию проекта, заполните форму
    ниже и отправьте сообщение.
  </p>
  <p>Вы так же можете <?= Html::a('отправить письмо', ['site/contact']); ?> по электронной почте </p>
  <div class="row">
    <div class="col-sm-12 mb-3 mb-sm-4">
        <?php $form = ActiveForm::begin(
            [
                'options' => ['disabled' => 'disabled']
            ]);
        ?>

        <?= $form->field($model, 'text')->textarea(['rows' => 5, 'disabled' => $disabled])->label(false) ?>

      <div class="form-group">
          <?= Html::submitButton('Отправить сообщение', ['class' => 'btn btn-primary btn-lg btn-flat', 'disabled' => $disabled]) ?>
      </div>

        <?php ActiveForm::end(); ?>
    </div>
  </div>
    <?php foreach ($dataProvider->getModels() as $feedback): ?>
      <div class="row">
        <div class="col-sm-12 mb-3 mb-sm-4">
          <div id="comment-<?= $feedback->getId(); ?>" class="card">
            <div class="card-header">
              <div class="d-flex justify-content-end">
                  <?= Yii::$app->formatter->asDatetime($feedback->created_at, 'php: H:i d.m.Y') ?>
              </div>
            </div>
            <div class="card-body">
              <p class="card-text"><?= $feedback->text; ?></p>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
</div>
