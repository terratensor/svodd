<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var string $url */

?>
<div class="verify-email">

    <p>Подтвердите адрес электронной почты::</p>
    <p><?= Html::a(Html::encode($url), $url) ?></p>
</div>
