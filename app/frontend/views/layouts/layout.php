<?php

/** @var View $this */

/** @var string $content */

use frontend\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\web\View;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
  <!DOCTYPE html>
  <html lang="<?= Yii::$app->language ?>" class="h-100" data-bs-theme="svodd">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <?php $this->registerCsrfMetaTags() ?>
      <?= Html::cssFile(!YII_DEBUG ? '@web/css/all.css' : '@web/css/all.min.css?v=' . filemtime(Yii::getAlias('@webroot/css/all.min.css'))) ?>
    <title><?= Html::encode($this->title) ?></title>
      <?php $this->head() ?>
      <?= $this->render('yandex_metrika'); ?>
  </head>
  <body class="d-flex flex-column h-100">
  <?php $this->beginBody() ?>

  <?= $this->render('header'); ?>

  <?= $content ?>

  <?= $this->render('footer'); ?>

  <?= Html::jsFile(YII_DEBUG ? '@web/js/all.js' : '@web/js/all.min.js?v=' . filemtime(Yii::getAlias('@webroot/js/all.min.js'))) ?>
  <?php $this->endBody() ?>
  </body>
  </html>
<?php $this->endPage();
