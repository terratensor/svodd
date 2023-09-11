<?php

declare(strict_types=1);

namespace frontend\assets;

use yii\web\AssetBundle;

class BsIconsAsset extends AssetBundle
{
    public $sourcePath = '@npm/bootstrap-icons';
    public $css = [
        'font/bootstrap-icons.min.css'
    ];
}
