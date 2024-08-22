<?php

/** @var Pagination $pagination */

use frontend\widgets\question\CommentSummary;
use yii\bootstrap5\Html;
use yii\data\Pagination;
use yii\helpers\Url;

?>
<div class="row">
    <div class="col-md-8 d-flex align-items-center">
        <?= CommentSummary::widget(['pagination' => $pagination]); ?>
    </div>
    <div class="col-md-4">
        <div class="d-flex align-items-start ">
            <label aria-label="Сортировка" for="input-sort"></label>
            <select id="input-sort" class="form-select mb-3" onchange="location = this.value;">
                <?php
                $values = [
                    '' => 'Сначала новые закладки',
                    '-date' => 'Сначала старые закладки',
                    'comment-date' => 'Сначала новые комментарии',
                    '-comment-date' => 'Сначала старые комментарии',
                ];
                $current = Yii::$app->request->get('sort');
                ?>
                <?php foreach ($values as $value => $label): ?>
                    <option value="<?= Html::encode(Url::current(['sort' => $value ?: null])) ?>"
                            <?php if ($current == $value): ?>selected="selected"<?php endif; ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>
