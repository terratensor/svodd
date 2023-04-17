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
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-sun-fill" viewBox="0 0 16 16">
  <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
</svg>
              <span class="d-lg-none ms-2" id="bd-theme-text">Переключение темы</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text">
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light" aria-pressed="true">
                  <svg class="bi me-2 opacity-50 theme-icon"><use href="#sun-fill"></use></svg>
                  Светлая
                  <svg class="bi ms-auto d-none"><use href="#check2"></use></svg>
                </button>
              </li>
              <li>
                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                  <svg class="bi me-2 opacity-50 theme-icon"><use href="#moon-stars-fill"></use></svg>
                  Тёмная
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
