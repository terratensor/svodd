<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var string $url */

?>
<div class="password-reset">
    <p>Перейдите по ссылке ниже, чтобы сбросить свой пароль:</p>

    <p><?= Html::a(Html::encode($url), $url) ?></p>
</div>
