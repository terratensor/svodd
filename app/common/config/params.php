<?php

use Twig\Loader\FilesystemLoader;

return [
    'adminEmail' => getenv('ADMIN_EMAIL'),
    'supportEmail' => getenv('SUPPORT_EMAIL'),
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,
    'user.rememberMeDuration' => 3600 * 24 * 30,
    'cookieDomain' => '.localhost',
    'frontendHostInfo' => getenv('FRONTEND_URL'),
    'manticore' => [
        'host' => 'manticore',
        'port' => 9308
    ],
    'auth' => [
        'token_ttl' => 'PT1H',
    ],
    'questionIndexFolder' => dirname(__DIR__, 2) . getenv('PARSED_FILES_DIR'),
    'questions' => [
        'pageSize' => (int)getenv('PAGE_SIZE'),
        'current' => [
            'id' => (int)getenv('CURRENT_QUESTION'),
            'file' => 'qa-question-view-6006.json'
        ],
        'url-pattern' => "https://фкт-алтай.рф/qa/question/view-",
    ],

    'from' => ['email' => getenv('MAILER_FROM_EMAIL'), 'name' => 'ФКТ поиск'],

    'mailer' => [
        'host' => getenv('MAILER_HOST'),
        'username' => getenv('MAILER_USERNAME'),
        'password' => trim(file_get_contents(getenv('MAILER_PASSWORD_FILE'))),
        'port' => (int)getenv('MAILER_PORT'),
    ],

//    'twig' => [
//        'debug' => (bool)getenv('APP_DEBUG'),
//        'template_dirs' => [
//            FilesystemLoader::MAIN_NAMESPACE => __DIR__ . '/../templates',
//        ],
//        'cache_dir' => __DIR__ . '/../var/cache/twig',
//        'extensions' => [],
//    ],
];
