<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'name' => 'ФКТ поиск',
    'language' => 'ru',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', \common\bootstrap\SetUp::class],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'db' => [
            'enableSchemaCache' => getenv('APP_ENV') === 'prod',
        ],
        'request' => [
            'csrfParam' => '_csrf-frontend',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'linkAssets' => true,
        ],
        'user' => [
            'identityClass' => 'common\auth\Identity',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity', 'httpOnly' => true, 'domain' => $params['cookieDomain']],
            'loginUrl' => ['auth/auth/login'],
        ],
        'session' => [
//            'class' => 'yii\web\DbSession',
            'class' => yii\redis\Session::class,
            // this is the name of the session cookie used for login on the frontend
            'name' => 'fct-search-session',
            'timeout' => 3600 * 24 * 7,
            'cookieParams' => [
                'domain' => $params['cookieDomain'],
                'httpOnly' => true,
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
                [
                    'class' => \App\Sentry\SentryTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'frontendUrlManager' => require __DIR__ . '/urlManager.php',
        'urlManager' => function () {
            return Yii::$app->get('frontendUrlManager');
        },
    ],
    'params' => $params,
];
