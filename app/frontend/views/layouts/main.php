<?php

/** @var View $this */

/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\web\View;

AppAsset::register($this);
?>
<?php $this->beginContent('@app/views/layouts/layout.php'); ?>
<main role="main" class="flex-shrink-0">
  <div class="container-fluid">
      <?= Breadcrumbs::widget(
          [
              'links' => $this->params['breadcrumbs'] ?? [],
          ]
      ) ?>
      <?= Alert::widget() ?>
      <?= $content ?>
  </div>
</main>
<?php $this->endContent() ?>
