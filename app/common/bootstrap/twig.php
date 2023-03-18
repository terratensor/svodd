<?php

declare(strict_types=1);

/** @var Container $container */

/** @var Application $app */

use App\Frontend\FrontendUrlGenerator;
use App\Frontend\FrontendUrlTwigExtension;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use yii\base\Application;
use yii\di\Container;

$container->setSingleton(\Twig\Environment::class, static function () use ($app) {
    $loader = new FilesystemLoader();

    foreach ($app->params['twig']['template_dirs'] as $alias => $dir) {
        $loader->addPath($dir, $alias);
    }

    $environment = new Environment($loader, [
        'cache' => $app->params['twig']['cache_dir'],
    ]);

    $environment->addExtension(
        new FrontendUrlTwigExtension(new FrontendUrlGenerator($app->params['frontendHostInfo'])));

    return $environment;
});
