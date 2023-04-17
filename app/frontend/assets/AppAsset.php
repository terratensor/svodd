<?php

namespace frontend\assets;

use yii\bootstrap5\BootstrapPluginAsset;
use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/docs.css',
        'css/site.css',
        'css/light_red.css', 
        'css/dark_red.css',
    ];
    public $js = [
        'js/script.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset',
        BootstrapPluginAsset::class
    ];
}
