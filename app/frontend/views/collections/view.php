<?php

declare(strict_types=1);

use yii\helpers\Html;
use yii\helpers\Url;

/** @var $provider \yii\data\ArrayDataProvider */
/** @var $this \yii\web\View */
/** @var $searchResults array */
/** @var App\UrlShortener\Service\ViewMyHandler $handler */

?>
<div class="row">
    <div class="col-md-8 d-flex align-items-center">
        <h5>Мои короткие ссылки</h5>
    </div>
    <div class="col-md-4">
        <div class="d-flex align-items-start ">

        </div>
    </div>
</div>

<ol class="list-group list-group-numbered">

    <?php foreach ($searchResults as $model): ?>

        <?php $response = $handler->handle($model->short_link);
        $model = json_decode($response, true)[0];

        ?>
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <div class="fw-bold"><?= $model['search'] ?></div>
                <?= Html::a(Yii::$app->urlManager->createAbsoluteUrl("/$model[short]"), Yii::$app->urlManager->createAbsoluteUrl("/$model[short]"));
                ?>
            </div>
            <span class="badge text-bg-primary rounded-pill"><?= $model['redirect_count']; ?></span>
        </li>
    <?php endforeach; ?>
</ol>