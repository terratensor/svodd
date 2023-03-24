<?php

return [
    'components' => [
        'db' => [
            'class' => \yii\db\Connection::class,
            'dsn' => 'pgsql:host=app-postgres;dbname=' . getenv('POSTGRES_DB'),
            'username' => getenv('POSTGRES_USER'),
            'password' => trim(file_get_contents(getenv('POSTGRES_PASSWORD_FILE'))),
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            // send all mails to a file by default.
            'useFileTransport' => true,
        ],
    ],
];
