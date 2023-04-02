<?php

declare(strict_types=1);

namespace frontend\assets\ChartJs;

use yii\web\AssetBundle;

class ChartJsMainAsset extends AssetBundle
{
    public $sourcePath = '@npm/chart.js';

    public $js = [
        'dist/chart.umd.js'
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}
