<?php

use yii\bootstrap5\Html;

?>
<footer class="footer mt-auto py-3 text-muted">
  <div class="container-fluid">
    <div class="d-flex flex-md-row flex-column flex-row align-items-baseline justify-content-between">
      <span><?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?>
          <?php
          if (Yii::$app->user->isGuest) {
              echo Html::a('Вход', ['/auth/auth/login'], ['class' => ['']]);
          } else {
              echo Html::a('Выход (' . Yii::$app->user->identity->getEmail() . ')', ['/auth/auth/logout'], ['data-method' => 'post']);
          }
          ?>
      </span>
      <div>
        <span><?= Html::a('Полнотекстовые операторы', ['userguide/index']); ?></span>  
        <span><?= Html::a('Обратная связь', ['site/contact']); ?></span>
      </div>
    </div>
  </div>
</footer>
