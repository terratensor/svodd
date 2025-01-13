<?php

declare(strict_types=1);

use yii\grid\GridView;

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'sid',
        'suggestion',
    ],
]);
