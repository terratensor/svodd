<?php

declare(strict_types=1);

namespace frontend\assets\ChartJs;

use yii\web\AssetBundle;

class ChartJsAsset extends AssetBundle
{
    public $depends = [
        ChartJsDatalabelsAsset::class
    ];
}
