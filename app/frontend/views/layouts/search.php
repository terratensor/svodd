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
  <html lang="<?= Yii::$app->language ?>" class="h-100" data-bs-theme="light">
  <head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <?php $this->registerCsrfMetaTags() ?>
      <?= $this->render('favicon'); ?>
    <title><?= Html::encode($this->title) ?></title>
      <?php $this->head() ?>
      <?= $this->render('yandex_metrika'); ?>
  </head>
  <body class="d-flex flex-column h-100">
  <?php $this->beginBody() ?>

  <?= $this->render('header'); ?>

  <main role="main" class="flex-shrink-0 mb-3">
    <div class="container-fluid pb-0">
        <?= Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
        <?= Alert::widget() ?>
    </div>

      <?= $content ?>

  </main>

  <?= $this->render('footer'); ?>

  <?php $this->endBody() ?>
  </body>
  </html>
<?php $this->endPage();
