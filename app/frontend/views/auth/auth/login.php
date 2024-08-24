<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var App\Auth\Form\Login\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag(['name' => 'robots', 'content' => 'noindex, nofollow']);
?>
<div class="site-login">
  <h4><?= Html::encode($this->title) ?></h4>

  <p>Введите email и пароль для входа <br>
    или <?= Html::a('зарегистрируйтесь', ['auth/join/request']); ?></p>

  <div class="row">
    <div class="col-lg-5">
      <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

      <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

      <?= $form->field($model, 'password')->passwordInput() ?>

      <?= $form->field($model, 'rememberMe')->checkbox() ?>

      <div class="my-1 mx-0" style="color:#999;">
        Если вы забыли пароль, вы можете его <?= Html::a('сбросить', ['auth/reset/password-request']) ?>
        <br>
        Нужно подтверждение email? <?= Html::a('Отправить повторно', ['auth/join/resend']) ?>
      </div>

      <div class="form-group">
        <?= Html::submitButton('Вход', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
      </div>

      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>