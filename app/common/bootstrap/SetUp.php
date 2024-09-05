<?php

declare(strict_types=1);

namespace common\bootstrap;


use App\Auth\Service\Tokenizer;
use App\FeatureToggle\Feature;
use App\FeatureToggle\FeatureFlag;
use App\Frontend\FrontendUrlGenerator;
use App\Indexer\Service\IndexerService;
use App\Indexer\Service\IndexFromDB\Handler;
use App\Indexer\Service\QuestionIndexService;
use App\dispatchers\AppEventDispatcher;
use App\dispatchers\SimpleAppEventDispatcher;
use App\Question\Entity\listeners\CommentCreatedListener;
use App\Question\Entity\Question\events\CommentCreated;
use App\repositories\Question\QuestionRepository;
use App\services\Manticore\IndexService;
use App\Svodd\Entity\Chart\events\StartCommentDataIDSetter;
use App\Svodd\Entity\listeners\CommentDataIDSetterListener;
use DateInterval;
use Manticoresearch\Client;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Mailer\EventListener\EnvelopeListener;
use Yii;
use yii\di\Container;
use yii\base\BootstrapInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Transport\Smtp\EsmtpTransport;
use Symfony\Component\Mime\Address;
use yii\rbac\ManagerInterface;

/**
 * Class SetUp
 * @packaage common\bootstrap
 * @author Aleksey Gusev <audetv@gmail.com>
 */
class SetUp implements BootstrapInterface
{

    /**
     * @throws \Exception
     */
    public function bootstrap($app): void
    {
        $container = Yii::$container;

        $container->setSingleton(ManagerInterface::class, function () use ($app) {
            return $app->authManager;
        });

        $container->setSingleton(MailerInterface::class, function () use ($app) {

            $dispatcher = new EventDispatcher();

            $dispatcher->addSubscriber(
                new EnvelopeListener(
                    new Address(
                        Yii::$app->params['from']['email'],
                        Yii::$app->params['from']['name']
                    )
                )
            );

            $transport = (new EsmtpTransport(
                Yii::$app->params['mailer']['host'],
                Yii::$app->params['mailer']['port'],
                false,
                $dispatcher,
            ))
                ->setUsername(Yii::$app->params['mailer']['username'])
                ->setPassword(Yii::$app->params['mailer']['password']);

            return new Mailer($transport);
        });

        $container->setSingleton(FrontendUrlGenerator::class, function () use ($app) {
            return new FrontendUrlGenerator($app->params['frontendHostInfo']);
        });

        $container->setSingleton(IndexService::class, [], [
            new Client($app->params['manticore']),
        ]);

        $container->setSingleton(IndexerService::class, [], [
            new Client($app->params['manticore']),
        ]);

        $container->setSingleton(QuestionIndexService::class, [], [
            new Client($app->params['manticore']),
        ]);

        $container->setSingleton(Handler::class, [], [
            new Client($app->params['manticore']),
        ]);

        $container->setSingleton(\App\Indexer\Service\UpdatingIndex\Handler::class, [], [
            new Client($app->params['manticore']),
        ]);

        $container->setSingleton(QuestionRepository::class, [], [
            new Client($app->params['manticore']),
            $app->params['questions']['pageSize'],
        ]);

        $container->setSingleton(Tokenizer::class, [], [
            new DateInterval($app->params['auth']['token_ttl'])
        ]);

        $container->setSingleton(FrontendUrlGenerator::class, [], [
            Yii::$app->params['frontendHostInfo'],
        ]);

        $container->setSingleton(FeatureFlag::class, function () use ($app) {
            $config = Yii::$app->params['feature-toggle'];
            return new Feature($config['features']);
        });

        $container->setSingleton(AppEventDispatcher::class, SimpleAppEventDispatcher::class);

        $container->setSingleton(SimpleAppEventDispatcher::class, function (Container $container) {
            return new SimpleAppEventDispatcher($container, [
                CommentCreated::class => [CommentCreatedListener::class],
                StartCommentDataIDSetter::class => [CommentDataIDSetterListener::class]
            ]);
        });

        $container->setSingleton(\App\UrlShortener\Command\Create\Request\Handler::class, [], [
            new \yii\httpclient\Client(
                [
                    'transport' => 'yii\httpclient\CurlTransport'
                ]
            )
        ]);

        $container->setSingleton(\App\UrlShortener\Service\ViewAllHandler::class, [], [
            new \yii\httpclient\Client(
                [
                    'transport' => 'yii\httpclient\CurlTransport'
                ]
            )
        ]);

        require __DIR__ . '/twig.php';
    }
}
