<?php

use App\helpers\SessionHelper;
use yii\bootstrap5\Nav;

$menuItems = [
    [
        'label' => 'Поиск',
        'url' => ['/site/index'],
        'linkOptions' => ['class' => 'nav-link py-2 px-0 px-lg-2'],
        'options' => ['class' => 'nav-item col-6 col-lg-auto'],
    ],
    [
        'label' => 'СВОДД',
        'url' => ['/svodd/index'],
        'linkOptions' => ['class' => 'nav-link py-2 px-0 px-lg-2'],
        'options' => ['class' => 'nav-item col-6 col-lg-auto'],
    ],
    [
        'label' => 'Обсуждение',
        'url' => SessionHelper::svoddUrl(Yii::$app->session),
        'linkOptions' => ['class' => 'nav-link py-2 px-0 px-lg-2'],
        'options' => ['class' => 'nav-item col-6 col-lg-auto'],
    ],
    [
        'label' => 'Вопросы',
        'url' => ['/question/index'],
        'linkOptions' => ['class' => 'nav-link py-2 px-0 px-lg-2'],
        'options' => ['class' => 'nav-item col-6 col-lg-auto'],
    ],
];

?>
<header class="navbar navbar-expand-lg navbar-dark bd-navbar fixed-top">
    <nav class="container-xxl bd-gutter flex-wrap flex-lg-nowrap" aria-label="Main navigation">
        <div class="d-lg-none" style="width: 2.25rem;"></div>
        <a class="navbar-zvezda p-0 me-0 me-lg-2" href="<?= Yii::$app->params['frontendHostInfo']; ?>"
           aria-label="<?= Yii::$app->name; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="32" class="d-block my-1" viewBox="0 0 516 515"
                 role="img"><title><?= Yii::$app->name; ?></title>
                <path
                    d="M258 401.5L417.5 515L360.5 319.5L516 201L320 196L258 0L196 196L0 201L155.5 319.5L98.5 515L258 401.5Z"
                    fill="#CC0000" />
            </svg>
        </a>
        <button class="navbar-toggler d-flex d-lg-none order-3 p-2" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#bdNavbar" aria-controls="bdNavbar" aria-expanded="false"
                aria-label="Toggle navigation">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots"
                 viewBox="0 0 16 16">
                <path
                    d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" />
            </svg>
        </button>
        <div class="offcanvas-lg offcanvas-end flex-grow-1" id="bdNavbar" aria-labelledby="bdNavbarOffcanvasLabel">
            <div class="offcanvas-header px-4 pb-0">
                <h5 class="offcanvas-title text-white" id="bdNavbarOffcanvasLabel">ФКТ Поиск</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"
                        data-bs-target="#bdNavbar"></button>
            </div>
            <div class="offcanvas-body p-4 pt-0 p-lg-0">
                <hr class="d-lg-none text-white-50">
                <?php echo Nav::widget(
                    [
                        'options' => ['class' => 'navbar-nav flex-row flex-wrap bd-navbar-nav'],
                        'items' => $menuItems,
                    ]); ?>
                <hr class="d-lg-none text-white-50">
                <ul class="navbar-nav flex-row flex-wrap ms-md-auto">
                    <li class="nav-item col-6 col-lg-auto">
                        <a class="nav-link py-2 px-0 px-lg-2" href="https://github.com/audetv/fct-search"
                           target="_blank" rel="noopener">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" class="navbar-nav-svg"
                                 viewBox="0 0 512 499.36" role="img"><title>GitHub</title>
                                <path fill="currentColor" fill-rule="evenodd"
                                      d="M256 0C114.64 0 0 114.61 0 256c0 113.09 73.34 209 175.08 242.9 12.8 2.35 17.47-5.56 17.47-12.34 0-6.08-.22-22.18-.35-43.54-71.2 15.49-86.2-34.34-86.2-34.34-11.64-29.57-28.42-37.45-28.42-37.45-23.27-15.84 1.73-15.55 1.73-15.55 25.69 1.81 39.21 26.38 39.21 26.38 22.84 39.12 59.92 27.82 74.5 21.27 2.33-16.54 8.94-27.82 16.25-34.22-56.84-6.43-116.6-28.43-116.6-126.49 0-27.95 10-50.8 26.35-68.69-2.63-6.48-11.42-32.5 2.51-67.75 0 0 21.49-6.88 70.4 26.24a242.65 242.65 0 0 1 128.18 0c48.87-33.13 70.33-26.24 70.33-26.24 14 35.25 5.18 61.27 2.55 67.75 16.41 17.9 26.31 40.75 26.31 68.69 0 98.35-59.85 120-116.88 126.32 9.19 7.9 17.38 23.53 17.38 47.41 0 34.22-.31 61.83-.31 70.23 0 6.85 4.61 14.81 17.6 12.31C438.72 464.97 512 369.08 512 256.02 512 114.62 397.37 0 256 0z"></path>
                            </svg>
                            <small class="d-lg-none ms-2">GitHub</small>
                        </a>
                    </li>
                    <li class="nav-item py-2 py-lg-1 col-12 col-lg-auto">
                        <div class="vr d-none d-lg-flex h-100 mx-lg-2 text-white"></div>
                        <hr class="d-lg-none my-2 text-white-50">
                    </li>
                    <li class="nav-item dropdown">
                        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                            <symbol id="circle-half" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"></path>
                            </symbol>
                            <symbol id="moon-stars-fill" viewBox="0 0 16 16">
                                <path
                                    d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"></path>
                                <path
                                    d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"></path>
                            </symbol>
                            <symbol id="sun-fill" viewBox="0 0 16 16">
                                <path
                                    d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"></path>
                            </symbol>
                        </svg>
                        <button
                            class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center"
                            id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown"
                            data-bs-display="static" aria-label="Переключить тему (светлая)">
                            <svg class="bi my-1 theme-icon-active">
                                <use href="#sun-fill"></use>
                            </svg>
                            <span class="d-lg-none ms-2" id="bd-theme-text">Переключить тему</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text">
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center active"
                                        data-bs-theme-value="light" aria-pressed="true">
                                    <svg class="bi me-2 opacity-50 theme-icon">
                                        <use href="#sun-fill"></use>
                                    </svg>
                                    Светлая
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2 ms-auto d-none" viewBox="0 0 16 16">
                                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center"
                                        data-bs-theme-value="dark" aria-pressed="false">
                                    <svg class="bi me-2 opacity-50 theme-icon">
                                        <use href="#moon-stars-fill"></use>
                                    </svg>
                                    Тёмная
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2 ms-auto d-none" viewBox="0 0 16 16">
                                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false">
                                    <svg class="bi me-2 opacity-50 theme-icon"><use href="#circle-half"></use></svg>
                                    Авто
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2 ms-auto d-none" viewBox="0 0 16 16">
                                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                    </svg>
                                </button>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
