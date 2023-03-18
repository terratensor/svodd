<?php

/** @var \yii\web\View $this */

/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

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

  <header>
      <?php
      NavBar::begin([
                        'brandLabel' => Yii::$app->name,
                        'brandUrl' => Yii::$app->homeUrl,
                        'options' => [
                            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
                        ],
                    ]);
      $menuItems = [
          ['label' => 'Поиск', 'url' => ['/site/index']],
          [
              'label' => 'Обсуждение',
              'url' => ['/question/view', 'id' => Yii::$app->params['questions']['current']['id']]
          ],
          ['label' => 'Список вопросов', 'url' => ['/questions']],
//        ['label' => 'О проекте', 'url' => ['/site/about']],
          ['label' => 'Обратная связь', 'url' => ['/site/contact']],
      ];
      if (Yii::$app->user->isGuest) {
          $menuItems[] = ['label' => 'Присоединиться', 'url' => ['/auth/join/request']];
      }

      echo Nav::widget([
                           'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
                           'items' => $menuItems,
                       ]);
      if (Yii::$app->user->isGuest) {
          echo Html::tag('div', Html::a('Вход', ['/auth/auth/login'], ['class' => ['btn btn-link login text-decoration-none']]), ['class' => ['d-flex']]);
      } else {
          echo Html::beginForm(['/auth/auth/logout'], 'post', ['class' => 'd-flex'])
              . Html::submitButton(
                  'Выход (' . Yii::$app->user->identity->getEmail() . ')',
                  ['class' => 'btn btn-link logout text-decoration-none']
              )
              . Html::endForm();
      }
      NavBar::end();
      ?>
  </header>

  <main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
                                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                                ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
    <div id="toTop" style="display: block;"></div>
  </main>

  <footer class="footer mt-auto py-3 text-muted">
    <div class="container">
      <div class="d-flex align-items-baseline justify-content-between">
        <span>&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></span>
        <span><?= Html::tag(
                'a',
                '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-github" viewBox="0 0 16 16">
  <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.012 8.012 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
</svg>',
                [
                    'class ' => 'btn btn-link link-dark',
                    'aria-label' => "Посетите github ФКТ поиск аккаунт",
                    'href' => 'https://github.com/audetv/fct-search',
                    'target' => "_blank",
                    'rel' => "noopener noreferrer",

                ]); ?></span>
      </div>
    </div>
  </footer>

  <?php $this->endBody() ?>
  </body>
  </html>
<?php $this->endPage();
