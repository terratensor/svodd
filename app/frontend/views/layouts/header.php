<?php

declare(strict_types=1);;

use App\helpers\SessionHelper;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

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
    NavBar::end();
    ?>
</header>
