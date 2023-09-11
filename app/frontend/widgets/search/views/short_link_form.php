<?php

declare(strict_types=1);

use yii\bootstrap5\ActiveForm;

$model = new \App\UrlShortener\Form\CreateLink\CreateForm();

$buttonTitle = 'Скопировать в буфер обмена'

?>
<?php $form = ActiveForm::begin(
    [
        'id' => 'createShortLinkForm',
        'action' => ['short-link']
    ]); ?>

<?= $form->field($model, 'origin', ['options' =>['tag' => null]])->hiddenInput()->label(false) ?>

<div class="row g-3">
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="inputShortLink1" placeholder="Короткая ссылка ★" aria-label="Короткая ссылка ★" aria-describedby="buttonInputShortLink1">
      <button
              class="btn btn-primary"
              type="button"
              id="buttonInputShortLink1"
              title="<?= $buttonTitle; ?>"
      ><i class="bi bi-clipboard"></i></button>
    </div>
</div>

<div class="row g-3">
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="inputShortLink2" placeholder="Короткая ссылка" aria-label="Короткая ссылка" aria-describedby="buttonInputShortLink2">
      <button
              class="btn btn-primary"
              type="button"
              id="buttonInputShortLink2"
              title="<?= $buttonTitle; ?>"
      ><i class="bi bi-clipboard"></i></button>
    </div>
</div>

<div class="row g-3">
  <div class="input-group mb-3">
    <input type="text" class="form-control" id="inputShortLink3" placeholder="★ Короткая ссылка ★"
           aria-label="★ Короткая ссылка ★" aria-describedby="buttonInputShortLink3">
    <button
            class="btn btn-primary"
            type="button"
            id="buttonInputShortLink3"
            title="<?= $buttonTitle; ?>"
    ><i class="bi bi-clipboard"></i></button>
  </div>
</div>

<?php ActiveForm::end(); ?>
