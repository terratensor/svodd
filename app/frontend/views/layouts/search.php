<?php

declare(strict_types=1);

/** @var \yii\web\View $this */

/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
  <!DOCTYPE html>
  <html lang="<?= Yii::$app->language ?>" class="h-100">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
      <?php $this->head() ?>
  </head>
  <body class="d-flex flex-column h-100">
  <?php $this->beginBody() ?>

  <?= $this->render('header'); ?>

  <main role="main" class="flex-shrink-0">
    <div class="container pb-0">
        <?= Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
        <?= Alert::widget() ?>
    </div>

      <?= $content ?>

    <div id="toTop" style="display: block;"></div>
  </main>

  <?= $this->render('footer'); ?>

  <?php $this->endBody() ?>
  </body>
  </html>
<?php $this->endPage();
