<?php

declare(strict_types=1);

use yii\bootstrap5\ActiveForm;

$model = new \App\UrlShortener\Form\CreateLink\CreateForm();

?>
<?php $form = ActiveForm::begin(
    [
        'id' => 'createShortLinkForm',
        'action' => ['short-link']
    ]); ?>

<?= $form->field($model, 'origin', ['options' =>['tag' => null]])->hiddenInput()->label(false) ?>

<h3 id="shortLinkResult" class=""></h3>

<?php ActiveForm::end(); ?>
