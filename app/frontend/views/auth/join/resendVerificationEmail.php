<?php

/** @var yii\web\View$this  */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var ResendVerificationEmailForm $model */

use App\Auth\Form\JoinByEmail\ResendVerificationEmailForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Подтверждение email';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-resend-verification-email">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста, заполните адрес электронной почты, на него будет отправлено электронное письмо с подтверждением.</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'resend-verification-email-form']); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

            <div class="form-group">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
