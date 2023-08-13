<?php

declare(strict_types=1);

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var UpdateTopicForm $model */

use App\Cabinet\Form\UpdateTopic\UpdateTopicForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Смена активной СВОДДной темы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="update-topic">
    <h4><?= Html::encode($this->title) ?></h4>

<div class="row">
    <div class="col-lg-5">
        <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['autocomplete' => 'off']]); ?>

        <?= $form->field($model, 'url')->textInput(['autofocus' => true])->label('Адрес страницы вопроса, следующей темы') ?>

        <?= $form->field($model, 'number')->textInput()->label('Номер следующей темы, например 37') ?>
        <?= $form->field($model, 'data_id')->textInput()->label('ИД комментария, открывающего новую тему') ?>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
</div>
