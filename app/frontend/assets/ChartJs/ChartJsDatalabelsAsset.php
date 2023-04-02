<?php

declare(strict_types=1);

namespace frontend\assets\ChartJs;

use yii\web\AssetBundle;

class ChartJsDatalabelsAsset extends AssetBundle
{
    public $sourcePath = '@npm/chartjs-plugin-datalabels';

    public $js = [
        'dist/chartjs-plugin-datalabels.js'
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public $depends = [
        ChartJsMainAsset::class
    ];
}
