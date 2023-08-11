<?php

/** @var View $this */

/** @var string $content */

use frontend\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\web\View;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
?>
<?php $this->beginPage() ?>
  <!DOCTYPE html>
  <html lang="<?= Yii::$app->language ?>" class="h-100" data-bs-theme="light">
  <head>
      <?= $this->render('favicon'); ?>
    <title><?= Html::encode($this->title) ?></title>
    <script src="/js/color-mode-toggler.js"></script>
      <?php $this->head() ?>
      <?= $this->render('yandex_metrika'); ?>
  </head>
  <body class="d-flex flex-column h-100">
  <?php $this->beginBody() ?>

  <?= $this->render('red_header'); ?>

  <?= $content ?>

  <?= $this->render('footer'); ?>

  <?php $this->endBody() ?>
  </body>
  </html>
<?php $this->endPage();
