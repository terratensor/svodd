<?php
declare(strict_types=1);

namespace common\bootstrap;


use App\Auth\Service\Tokenizer;
use App\Frontend\FrontendUrlGenerator;
use App\repositories\Question\QuestionRepository;
use App\services\Manticore\IndexService;
use DateInterval;
use Manticoresearch\Client;
use Yii;
use yii\mail\MailerInterface;
use yii\base\BootstrapInterface;

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
    public function bootstrap($app)
    {
        $container = Yii::$container;

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(IndexService::class, [], [
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
    }
}
