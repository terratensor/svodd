<?php

declare(strict_types=1);

namespace frontend\assets;

use yii\web\AssetBundle;

class ShowdownAsset extends AssetBundle
{
    public $sourcePath = '@npm/showdown';

    public $js = [
        'dist/showdown.min.js'
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];
}