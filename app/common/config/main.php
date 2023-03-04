<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            // send all mails to a file by default.
            'useFileTransport' => false,
            'transport' => [
//                    'scheme' => 'smtp',
                'host' => getenv('MAILER_HOST'),
                'username' => getenv('MAILER_USER'),
                'password' => getenv('MAILER_PASSWORD'),
                'port' => (int)getenv('MAILER_PORT'),
//                    'dsn' => 'native://default',
            ],
            'from' => ['email' => getenv('MAILER_FROM_EMAIL'), 'name' => 'ФКТ поиск'],
            //
            // DSN example:
            //    'transport' => [
            //        'dsn' => 'smtp://user:pass@smtp.example.com:25',
            //    ],
            //
            // See: https://symfony.com/doc/current/mailer.html#using-built-in-transports
            // Or if you use a 3rd party service, see:
            // https://symfony.com/doc/current/mailer.html#using-a-3rd-party-transport
        ],
    ],
];
