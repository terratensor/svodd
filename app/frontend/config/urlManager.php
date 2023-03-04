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
        'about' => 'site/about',
        'signup' => 'auth/join/request',
        'join/confirm' => 'auth/join/confirm',
        '<_a:login|logout>' => 'auth/auth/<_a>',

        [
            'pattern' => 'question/<id:\d+>/<page:\d+>',
            'route' => 'site/question',
            'defaults' => ['page' => 1],
        ],

        '<_c:[\w\-]+>' => '<_c>/index',
        '<_c:[\w\-]+>/<id:\d+>' => '<_c>/view',
        '<_c:[\w\-]+>/<_a:[\w-]+>' => '<_c>/<_a>',
        '<_c:[\w\-]+>/<id:\d+>/<_a:[\w\-]+>' => '<_c>/<_a>',
    ],
];
