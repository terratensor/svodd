<?php

declare(strict_types=1);

use yii\helpers\Html;
use yii\helpers\Url;

/** @var $provider \yii\data\ArrayDataProvider */

?>
<div class="row">
    <div class="col-md-8 d-flex align-items-center">
        <h5>Все короткие ссылки</h5>
    </div>
    <div class="col-md-4">
        <div class="d-flex align-items-start ">
            <label aria-label="Сортировка" for="input-sort"></label>
            <select id="input-sort" class="form-select mb-3" onchange="location = this.value;">
                <?php
                $values = [
                    '' => 'Сначала новые ссылки',
                    '-created_at' => 'Сначала старые ссылки',
                    'search' => 'По запросу в алфавитном порядке',
                    '-search' => 'По запросу в обратном алфавитном порядке',
                    '-redirect_count' => 'Количество переходов по убыванию',
                    'redirect_count' => 'Количеству переходов по возрастанию',
                ];
                $current = $sort = Yii::$app->request->get('sort') ?: '';

                ?>
                <?php foreach ($values as $value => $label) : ?>
                    <option value="<?= Html::encode(Url::current(['sort' => $value ?: null])) ?>" <?php if ($current == $value) : ?>selected="selected" <?php endif; ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

<ol class="list-group list-group-numbered">

    <?php foreach ($provider->getModels() as $model): ?>
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <div class="fw-bold"><?= $model['search']; ?></div>
                <?= Html::a(Yii::$app->urlManager->createAbsoluteUrl("/$model[short]"), Yii::$app->urlManager->createAbsoluteUrl("/$model[short]")); ?>
            </div>
            <span class="badge text-bg-primary rounded-pill"><?= $model['redirect_count']; ?></span>
        </li>
    <?php endforeach; ?>
</ol>