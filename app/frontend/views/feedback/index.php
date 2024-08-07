<?php

declare(strict_types=1);

use App\Auth\Entity\User\Role;
use App\Feedback\Entity\Feedback\Feedback;
use App\Feedback\Form\SendMessage\FeedbackForm;
use frontend\widgets\feedback\FeedbackMessagePanel;
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
  <h3><?= Html::encode($this->title) ?></h3>

  <p>
    Поделитесь своими мыслями, задайте вопрос, оставьте предложение или пожелание по развитию проекта.
  </p>
  <p>Вы так же можете <?= Html::a('отправить письмо', ['site/contact']); ?> по электронной почте.</p>
  <div class="row">
    <div class="col-sm-12 mb-3 mb-sm-4">
        <?php $form = ActiveForm::begin(
            [
                'options' => ['disabled' => 'disabled']
            ]);
        ?>

        <?= $form->field($model, 'text')->textarea(['rows' => 5, 'disabled' => $disabled])->label(false) ?>

      <div class="form-group">
          <?= Html::submitButton('Отправить сообщение', [
              'class' => 'btn btn-primary btn-lg btn-flat', 'disabled' => $disabled
          ]) ?>
      </div>

        <?php ActiveForm::end(); ?>
    </div>
  </div>
    <?php foreach ($dataProvider->getModels() as $feedback): ?>
      <div class="row">
        <div class="col-sm-12 mb-3 mb-sm-4">
          <div id="comment-<?= $feedback->getId(); ?>" class="card">
            <div class="card-header">
              <div class="d-flex justify-content-between">
                  <?= $feedback->allowedToEdit(new DateTimeImmutable()) ? '<p>Изменить или удалить сообщение можно в течение 10 минут после создания</p>' : ''; ?>
                  <?= Yii::$app->formatter->asDatetime($feedback->created_at, 'php: H:i d.m.Y') ?>
              </div>
            </div>
            <div class="card-body">
              <p class="card-text mb-4"><?= $feedback->text; ?></p>
                <?= FeedbackMessagePanel::widget(['feedback' => $feedback]); ?>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
</div>
