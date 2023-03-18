<?php

declare(strict_types=1);

/** @var Container $container */
/** @var Application $app */

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\ExtensionInterface;
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

//    foreach ($app->params['twig']['extensions'] as $class) {
//        /** @var ExtensionInterface $extension */
//        $extension = $class;
//        $environment->addExtension($class);
//    }

    return $environment;
});
