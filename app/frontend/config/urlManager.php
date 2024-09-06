<?php

declare(strict_types=1);

/** @var array $params */

return [
    'class' => 'yii\web\UrlManager',
    'hostInfo' => $params['frontendHostInfo'],
    'baseUrl' => '',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'cache' => false,
    'rules' => [
        '' => 'site/index',
        'contact' => 'site/contact',
        'feedback' => 'feedback/index',
        'feedback/update/<id:[\w\-]+>' => 'feedback/update',
        'feedback/delete/<id:[\w\-]+>' => 'feedback/delete',
        'about' => 'site/about',
        'svodd' => 'svodd/redirect',
        'graph' => 'svodd/index',
        'bolshaya-svoddnaya-tema' => 'svodd/view',
        'signup' => 'auth/join/request',
        'join/confirm' => 'auth/join/confirm',
        '<_a:login|logout>' => 'auth/auth/<_a>',
        'bookmarks' => 'bookmark/view',
        'collections/my' => 'collections/view',

        'questions' => 'question/index',
        [
            'pattern' => 'question-old/<id:\d+>/<page:\d+>',
            'route' => 'site/question',
            'defaults' => ['page' => 1],
        ],
        [
            'pattern' => 'question/<id:\d+>/<page:\d+>',
            'route' => 'question/view',
            'defaults' => ['page' => 0],
        ],

        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ],
];
