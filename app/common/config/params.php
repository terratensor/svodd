<?php

use App\Frontend\FrontendUrlTwigExtension;
use Twig\Loader\FilesystemLoader;

return [
    'adminEmail' => getenv('ADMIN_EMAIL'),
    'supportEmail' => getenv('SUPPORT_EMAIL'),
    'senderEmail' => 'noreply@example.com',
    'senderName' => 'Example.com mailer',
    'user.passwordResetTokenExpire' => 3600,
    'user.passwordMinLength' => 8,
    'user.rememberMeDuration' => 3600 * 24 * 30,
    'cookieDomain' => getenv('COOKIE_DOMAIN'),
    'frontendHostInfo' => getenv('FRONTEND_URL'),
    'staticHostInfo' => getenv('STATIC_URL'),
    'urlShortenerHost' => getenv('URL_SHORTENER_HOST'), // Хост в сети, в локальной сети это наименования сервиса
    'urlShortenerUrl' => getenv('URL_SHORTENER_URL'), // наименование домена, в локальной сети поддомен.localhost
    'maintenance_message' => 'На сайте проводятся технические работы по обновлению поискового индекса. Возможно, на ваш запрос, вы увидите только часть результатов поиска. Приносим извинения за неудобство.',
    'cut_telegram_links' => getenv('CUT_TELEGRAM_LINKS'),
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
            'file' => '32-qa-question-view-32649.json'
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

    'twig' => [
        'debug' => (bool)getenv('APP_DEBUG'),
        'template_dirs' => [
            FilesystemLoader::MAIN_NAMESPACE => __DIR__ . '/../../templates',
        ],
        'cache_dir' => __DIR__ . '/../../var/cache/twig',
        'extensions' => [
            FrontendUrlTwigExtension::class
        ],
    ],
    'feature-toggle' => [
        'features' => [
            '09051945B' => true,
        ],
    ],
    'indexes' => [
        'common' => 'questions',
        'concept' => 'questions_ext',
    ]
];
