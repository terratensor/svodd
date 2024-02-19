<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var ContactForm $model */

use App\Contact\Form\SendEmail\ContactForm;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Обратная связь';
$this->params['meta_description'] = 'Если у вас есть вопрос, предложение или пожелание по развитию проекта, пожалуйста заполните форму ниже и отправьте ваше письмо. Спасибо.';

$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile("https://smartcaptcha.yandexcloud.net/captcha.js?render=onload&onload=smartCaptchaInit", [
    'defer' => true, 'async' => true
]);

?>
  <div class="site-contact">
    <h3><?= Html::encode($this->title) ?></h3>

    <p>
      Если у вас есть вопрос, предложение или пожелание по развитию проекта, пожалуйста заполните форму ниже и отправьте
      ваше письмо. Спасибо.
    </p>

    <div class="row">
      <div class="col-lg-5">
          <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

          <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

          <?= $form->field($model, 'email') ?>

          <?= $form->field($model, 'subject') ?>

          <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

        <div
                id="captcha-container"
                class="smart-captcha mb-3"
                data-hl="ru"
        ></div>
          <?= $form->field($model, 'token')->hiddenInput(['id' => 'contactform-token', 'value' => ''])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton(
                'Отправить',
                [
                    'id' => 'smartcaptcha-demo-submit',
                    'class' => 'btn btn-primary',
                    'name' => 'contact-button',
                    'disabled' => 'disabled',
                ]) ?>
        </div>
          <?php ActiveForm::end(); ?>
      </div>
    </div>

  </div>

<?php $this->registerJsFile(Yii::getAlias('@web/js/yacaptcha.js'), ['position' => \yii\web\View::POS_END], 'smart-captcha');
