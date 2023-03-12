<?php
return [
    'adminEmail' => getenv('ADMIN_EMAIL'),
    'supportEmail' => 'support@example.com',
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
            'id' =>(int)getenv('CURRENT_QUESTION'),
            'file' => 'qa-question-view-8162.json'
        ],
        'url-pattern' => "https://фкт-алтай.рф/qa/question/view-",
    ],
    'from' => ['email' => getenv('MAILER_FROM_EMAIL'), 'name' => 'ФКТ поиск']
];
