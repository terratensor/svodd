<?php

declare(strict_types=1);;

use App\helpers\SessionHelper;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;


$html = <<<HTML
<ul class="navbar-nav flex-row flex-wrap ms-md-auto">
      <li class="nav-item dropdown">
            <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static" aria-label="Toggle theme (light)">
              <svg class="bi my-1 theme-icon-active"><use href="#sun-fill"></use></svg>
              <span class="d-lg-none ms-2" id="bd-theme-text">Toggle theme</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text">
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light" aria-pressed="true">
                  <svg class="bi me-2 opacity-50 theme-icon"><use href="#sun-fill"></use></svg>
                  Light
                  <svg class="bi ms-auto d-none"><use href="#check2"></use></svg>
                </button>
              </li>
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                  <svg class="bi me-2 opacity-50 theme-icon"><use href="#moon-stars-fill"></use></svg>
                  Dark
                  <svg class="bi ms-auto d-none"><use href="#check2"></use></svg>
                </button>
              </li>          
            </ul>
          </li>
</ul>
HTML;


$menuItems = [
    ['label' => 'Поиск', 'url' => ['/site/index']],
    ['label' => 'СВОДД', 'url' => ['/svodd/index']],
    [
        'label' => 'Обсуждение',
        'url' => SessionHelper::svoddUrl(Yii::$app->session),
    ],
    ['label' => 'Вопросы', 'url' => ['/question/index']],
];

?>
<header>
    <?php NavBar::begin(
        [
            'collapseOptions' => false,
            'innerContainerOptions' => ['class' => 'container-fluid'],
            'options' => [
                'class' => 'navbar navbar-expand navbar-dark bg-dark fixed-top'
            ],
        ]);

    echo Nav::widget(
        [
            'options' => ['class' => 'navbar-nav me-auto mb-md-0'],
            'items' => $menuItems,
        ]);

    echo $html;

    NavBar::end();
    ?>
</header>
