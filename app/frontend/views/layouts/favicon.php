<?php $this->registerLinkTag(
    [
        'rel' => 'icon', 'type' => 'image/png', 'sizes' => '16x16',
        'href' => Yii::$app->urlManager->createAbsoluteUrl('/favicon/favicon-16x16.png')
    ]
) ?>
<?php $this->registerLinkTag(
    [
        'rel' => 'icon', 'type' => 'image/png', 'sizes' => '32x32',
        'href' => Yii::$app->urlManager->createAbsoluteUrl('/favicon/favicon-32x32.png')
    ]
) ?>
<?php $this->registerLinkTag(
    [
        'rel' => 'icon', 'type' => 'image/png', 'sizes' => '192x192',
        'href' => Yii::$app->urlManager->createAbsoluteUrl('/favicon/android-chrome-192x192.png')
    ]
) ?>
<?php $this->registerLinkTag(
    [
        'rel' => 'icon', 'type' => 'image/png', 'sizes' => '512x512',
        'href' => Yii::$app->urlManager->createAbsoluteUrl('/favicon/android-chrome-512x512.png')
    ]) ?>
<?php $this->registerLinkTag(
    [
        'rel' => 'apple-touch-icon', 'sizes' => '180x180',
        'href' => Yii::$app->urlManager->createAbsoluteUrl('/favicon/apple-touch-icon.png')
    ]
) ?>
<?php $this->registerLinkTag(
    [
        'rel' => 'shortcut icon',
        'href' => Yii::$app->urlManager->createAbsoluteUrl('/favicon.ico')
    ]) ?>
<?php $this->registerLinkTag(
    [
        'rel' => 'manifest'
        , 'href' => Yii::$app->urlManager->createAbsoluteUrl('/favicon/site.webmanifest')
    ]) ?>
