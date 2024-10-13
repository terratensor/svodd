<?php

use App\helpers\BookmarkHelper;
use App\helpers\SessionHelper;
use App\helpers\SvgIconHelper;
use yii\bootstrap5\Nav;
use yii\web\View;

/** @var $this View */

// $searchIcon = '<svg class="menu-icon text-svoddRed-100" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="SearchIcon"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14"></path></svg>';
$questionAnswerIcon = SvgIconHelper::questionAnswerIcon();
$libIcon = '<svg class="menu-icon text-svoddRed-100" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="LibraryBooksOutlinedIcon"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4zm16-4H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2m0 14H8V4h12zM10 9h8v2h-8zm0 3h4v2h-4zm0-6h8v2h-8z"></path></svg>';
$svoddIcon = '<svg class="menu-icon text-svoddRed-100" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" data-slot="icon"><path d="M4.913 2.658c2.075-.27 4.19-.408 6.337-.408 2.147 0 4.262.139 6.337.408 1.922.25 3.291 1.861 3.405 3.727a4.403 4.403 0 0 0-1.032-.211 50.89 50.89 0 0 0-8.42 0c-2.358.196-4.04 2.19-4.04 4.434v4.286a4.47 4.47 0 0 0 2.433 3.984L7.28 21.53A.75.75 0 0 1 6 21v-4.03a48.527 48.527 0 0 1-1.087-.128C2.905 16.58 1.5 14.833 1.5 12.862V6.638c0-1.97 1.405-3.718 3.413-3.979Z"></path><path d="M15.75 7.5c-1.376 0-2.739.057-4.086.169C10.124 7.797 9 9.103 9 10.609v4.285c0 1.507 1.128 2.814 2.67 2.94 1.243.102 2.5.157 3.768.165l2.782 2.781a.75.75 0 0 0 1.28-.53v-2.39l.33-.026c1.542-.125 2.67-1.433 2.67-2.94v-4.286c0-1.505-1.125-2.811-2.664-2.94A49.392 49.392 0 0 0 15.75 7.5Z"></path></svg>';
$telegramIcon = '<svg class="menu-icon text-svoddRed-100" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="TelegramIcon"><path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z"></path></svg>';
$listIcon = '<svg class="menu-icon text-svoddRed-100" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="QuizIcon"><path d="M4 6H2v14c0 1.1.9 2 2 2h14v-2H4z"></path><path d="M20 2H8c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2m-5.99 13c-.59 0-1.05-.47-1.05-1.05 0-.59.47-1.04 1.05-1.04.59 0 1.04.45 1.04 1.04-.01.58-.45 1.05-1.04 1.05m2.5-6.17c-.63.93-1.23 1.21-1.56 1.81-.13.24-.18.4-.18 1.18h-1.52c0-.41-.06-1.08.26-1.65.41-.73 1.18-1.16 1.63-1.8.48-.68.21-1.94-1.14-1.94-.88 0-1.32.67-1.5 1.23l-1.37-.57C11.51 5.96 12.52 5 13.99 5c1.23 0 2.08.56 2.51 1.26.37.61.58 1.73.01 2.57"></path></svg>';
$chartIcon = '<svg class="menu-icon text-svoddRed-100 chart-icon" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="InsertChartIcon"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2M9 17H7v-7h2zm4 0h-2V7h2zm4 0h-2v-4h2z"></path></svg>';
$starIcon = '<svg class="menu-icon text-svoddRed-100 star-icon" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="StarRateIcon"><path d="M14.43 10 12 2l-2.43 8H2l6.18 4.41L5.83 22 12 17.31 18.18 22l-2.35-7.59L22 10z"></path></svg>';
$bookmarksFillIcon = '<svg class="menu-icon text-svoddRed-100 bookmark-fill-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#5f6368"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2z"/></svg>';
$bookmarksIcon = '<svg class="menu-icon text-svoddRed-100 bookmark-icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#5f6368"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M17 3H7c-1.1 0-2 .9-2 2v16l7-3 7 3V5c0-1.1-.9-2-2-2zm0 15l-5-2.18L7 18V5h10v13z"/></svg>';

// $hasBookmarks = BookmarkHelper::hasBookamrks();
// $bookmarkLabel = $hasBookmarks ? " $bookmarksFillIcon <div class=\"d-lg-none ms-0\">Закладки</div>" : " $bookmarksIcon <div class=\"d-lg-none ms-0\">Закладки</div>";

$menuItems = [
    [

        'label' => "Поиск",
        'url' => ['/site/index'],
        'linkOptions' => ['class' => 'nav-link py-2 px-0 px-lg-2'],
        'options' => ['class' => 'nav-item col-12 col-lg-auto d-none d-lg-inline'],
        'items' => [
            [
                'label' => "$questionAnswerIcon Вопросы и комментарии на ФКТ",
                'url' => ['/site/index'],
                'linkOptions' => ['class' => 'nav-link py-2 px-2 px-lg-2'],
                'options' => ['class' => 'nav-item col-12 col-lg-auto'],
            ],
            [
                'label' => "$libIcon Тексты толстых книг ВП СССР",
                'url' => 'https://kob.svodd.ru',
                'linkOptions' => ['class' => 'nav-link py-2 px-2 px-lg-2'],
                'options' => ['class' => 'nav-item col-12 col-lg-auto'],
            ],
            [
                'label' => "$libIcon Военно-историческая библиотека",
                'url' => 'https://lib.svodd.ru',
                'linkOptions' => ['class' => 'nav-link py-2 px-2 px-lg-2'],
                'options' => ['class' => 'nav-item col-12 col-lg-auto'],
            ],
            [
                'label' => "$starIcon <span>Сайты Кремля, МИД и Минобороны</span>",
                'url' => 'https://feed.svodd.ru',
                'linkOptions' => ['class' => 'nav-link py-2 px-2 px-lg-2 d-flex align-items-center'],
                'options' => ['class' => 'nav-item col-12 col-lg-auto'],
            ]
        ]
    ],
    [
        'label' => "$questionAnswerIcon Вопросы и комментарии на ФКТ",
        'url' => ['/site/index'],
        'linkOptions' => ['class' => 'nav-link py-2 px-0 px-lg-2'],
        'options' => ['class' => 'nav-item col-12 col-lg-auto d-sm-inline d-lg-none'],
    ],
    [
        'label' => "$libIcon Тексты толстых книг ВП СССР",
        'url' => 'https://kob.svodd.ru',
        'linkOptions' => ['class' => 'nav-link py-2 px-0 px-lg-2'],
        'options' => ['class' => 'nav-item col-12 col-lg-auto d-sm-inline d-lg-none'],
    ],
    [
        'label' => "$libIcon Военно-историческая библиотека",
        'url' => 'https://lib.svodd.ru',
        'linkOptions' => ['class' => 'nav-link py-2 px-0 px-lg-2'],
        'options' => ['class' => 'nav-item col-12 col-lg-auto d-sm-inline d-lg-none'],
    ],
    [
        'label' => "$starIcon <span>Сайты Кремля, МИД и Минобороны</span>",
        'url' => 'https://feed.svodd.ru',
        'linkOptions' => ['class' => 'nav-link py-2 px-0 px-lg-2 d-flex align-items-center'],
        'options' => ['class' => 'nav-item col-12 col-lg-auto d-sm-inline d-lg-none'],
    ],
    '<hr class="w-100 d-lg-none text-white-50">',
    [
        'label' => "<span class=\"d-lg-none\">$svoddIcon</span> Обсуждение СВОДД",
        'url' => SessionHelper::svoddUrl(Yii::$app->session),
        'linkOptions' => ['class' => 'nav-link py-2 px-0 px-lg-2'],
        'options' => ['class' => 'nav-item col-12 col-lg-auto'],
    ],
    [
        'label' => " $chartIcon <div class=\"d-lg-none ms-0\">Статистика и хронология обсуждения</div>",
        'url' => ['svodd/index'],
        'linkOptions' => [
            'class' => 'nav-link py-2 px-0 px-lg-2 d-flex align-items-center',
            'title' => 'Статистика и хронология обсуждения СВОДД'
        ],
        'options' => ['class' => 'd-lg-none ms-0 nav-item col-12 col-lg-auto'],
    ],
    [
        'label' => "<span class=\"d-lg-none\">$listIcon</span> Список вопросов",
        'url' => ['/question/index'],
        'linkOptions' => ['class' => 'nav-link py-2 px-0 px-lg-2'],
        'options' => ['class' => 'nav-item col-12 col-lg-auto'],
    ],
    '<hr class="w-100 d-lg-none text-white-50">',
    [
        'label' => "<span class=\"d-lg-none\">$starIcon</span> Короткие ссылки",
        'url' => ['collections/index'],
        'linkOptions' => ['class' => 'nav-link py-2 px-0 px-lg-2', 'rel' => 'nofollow, noindex'],
        'options' => ['class' => 'nav-item col-12 col-lg-auto'],
    ],
    // [
    //     'label' => $bookmarkLabel,
    //     'url' => ['bookmark/view'],
    //     'linkOptions' => [
    //         'class' => 'nav-link py-2 px-0 px-lg-2 d-flex align-items-center',
    //         'title' => 'Закладки'
    //     ],
    //     'options' => ['class' => 'd-lg-none ms-0 nav-item col-12 col-lg-auto'],
    // ],
    [
        'label' => " $chartIcon <div class=\"d-lg-none ms-0\">Статистика и хронология обсуждения</div>",
        'url' => ['svodd/index'],
        'linkOptions' => [
            'class' => 'nav-link py-2 px-0 px-lg-2 d-flex align-items-center',
            'title' => 'Статистика и хронология обсуждения СВОДД'
        ],
        'options' => ['class' => 'd-none d-lg-block d-xl-block nav-item col-12 col-lg-auto'],
    ],
    // [
    //     'label' => $bookmarkLabel,
    //     'url' => ['bookmark/view'],
    //     'linkOptions' => [
    //         'class' => 'nav-link py-2 px-0 px-lg-0 d-flex align-items-center',
    //         'title' => 'Закладки',
    //     ],
    //     'options' => ['class' => 'd-none d-lg-block d-xl-block nav-item col-12 col-lg-auto'],
    // ],
];

?>
<header class="navbar navbar-expand-lg navbar-dark bd-navbar fixed-top">
    <nav class="container-xxl bd-gutter flex-wrap flex-lg-nowrap" aria-label="Main navigation">
        <div class="d-lg-none" style="width: 2.25rem;"></div>
        <a class="navbar-zvezda p-0 me-0 me-lg-2" href="<?= Yii::$app->params['frontendHostInfo']; ?>" aria-label="<?= Yii::$app->name; ?>">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="32" class="d-block my-1" viewBox="0 0 516 515" role="img">
                <title><?= Yii::$app->name; ?></title>
                <path d="M258 401.5L417.5 515L360.5 319.5L516 201L320 196L258 0L196 196L0 201L155.5 319.5L98.5 515L258 401.5Z" fill="#CC0000" />
            </svg>
        </a>
        <button class="navbar-toggler d-flex d-lg-none order-3 p-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#bdNavbar" aria-controls="bdNavbar" aria-expanded="false" aria-label="Toggle navigation" id="menuButton">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-three-dots" viewBox="0 0 16 16">
                <path d="M3 9.5a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3z" />
            </svg>
        </button>
        <div class="offcanvas-lg offcanvas-end flex-grow-1" id="bdNavbar" aria-labelledby="bdNavbarOffcanvasLabel">
            <div class="offcanvas-header px-4 pb-0">
                <h6 class="offcanvas-title text-white" id="bdNavbarOffcanvasLabel">Источники концептуального поиска</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close" data-bs-target="#bdNavbar" id="menuCloseButton"></button>
            </div>
            <div class="offcanvas-body p-4 pt-0 p-lg-0">
                <hr class="d-lg-none text-white-50">
                <?php echo Nav::widget(
                    [
                        'options' => [
                            'class' => 'navbar-nav flex-row flex-wrap bd-navbar-nav',
                        ],
                        'encodeLabels' => false,
                        'items' => $menuItems,
                    ]
                ); ?>
                <hr class="d-lg-none text-white-50">
                <ul class="navbar-nav flex-row flex-wrap ms-md-auto">

                    <li class="nav-item col-6 col-lg-auto">
                        <a class="nav-link py-2 px-0 px-lg-2" href="https://t.me/svoddru" target="_blank" rel="noopener">
                            <?= $telegramIcon; ?>
                            <small class="d-lg-none ms-2">@svoddru</small>
                        </a>
                    </li>
                    <li class="nav-item py-2 py-lg-1 col-12 col-lg-auto">
                        <div class="vr d-none d-lg-flex h-100 mx-lg-2 text-white"></div>
                        <hr class="d-lg-none my-2 text-white-50">
                    </li>
                    <li class="nav-item dropdown theme-mode">
                        <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                            <symbol id="circle-half" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"></path>
                            </symbol>
                            <symbol id="moon-stars-fill" viewBox="0 0 16 16">
                                <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"></path>
                                <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"></path>
                            </symbol>
                            <symbol id="sun-fill" viewBox="0 0 16 16">
                                <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"></path>
                            </symbol>
                        </svg>
                        <button class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" data-bs-display="static" aria-label="Переключить тему (светлая)" title="Переключить тему">
                            <svg class="bi my-1 theme-icon-active theme-icon-color">
                                <use href="#sun-fill"></use>
                            </svg>
                            <span class="d-lg-none ms-2" id="bd-theme-text">Переключить тему</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text">
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="light" aria-pressed="true">
                                    <svg class="bi me-2 opacity-50 theme-icon">
                                        <use href="#sun-fill"></use>
                                    </svg>
                                    Светлая
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2 ms-auto d-none" viewBox="0 0 16 16">
                                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                                    <svg class="bi me-2 opacity-50 theme-icon">
                                        <use href="#moon-stars-fill"></use>
                                    </svg>
                                    Тёмная
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2 ms-auto d-none" viewBox="0 0 16 16">
                                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="auto" aria-pressed="false">
                                    <svg class="bi me-2 opacity-50 theme-icon">
                                        <use href="#circle-half"></use>
                                    </svg>
                                    Авто
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check2 ms-auto d-none" viewBox="0 0 16 16">
                                        <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
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

<?php $js = <<<JS
const menuOffcanvas = document.getElementById('bdNavbar')
const togglerButton = document.getElementById('menuButton')







togglerButton.addEventListener('click', event => {
  const dropdownElementList = document.querySelectorAll('.dropdown-toggle')
const dropdownList = [...dropdownElementList].map(dropdownToggleEl => new bootstrap.Dropdown(dropdownToggleEl))
// console.log(dropdownList)
 //console.log(dropdownList[0])
 const dropdown = dropdownList[0]

 var myDropdown = bootstrap.Dropdown.getInstance($("#searchDropdownItem")[0]);
  setTimeout(function() {
    console.log(myDropdown.toggle());
}, (100));
        
//console.log(dropdown)
//dropdown.show()
//console.log(event)
// dropdownList[0].show()
})

JS;

//$this->registerJs($js);
