<?php

declare(strict_types=1);
;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

$menuItems = [
    ['label' => 'Поиск', 'url' => ['/site/index']],
    ['label' => 'СВОДД', 'url' => ['/svodd/index']],
    [
        'label' => 'Обсуждение',
        'url' => array_merge(['/svodd/view'], Yii::$app->session->get('svodd') ?? []),
        // добавляем параметры предыдущего запроса к ссылке на обсуждение, чтобы восстановить сортировку и страницу
    ],
    ['label' => 'Вопросы', 'url' => ['/question/index']],
];

?>
<header>
    <?php NavBar::begin(
        [
            'collapseOptions' => false,
            'options' => [
                'class' => 'navbar navbar-expand navbar-dark bg-dark fixed-top'
            ],
        ]);

    echo Nav::widget(
        [
            'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
            'items' => $menuItems,
        ]);
    NavBar::end();
    ?>
</header>