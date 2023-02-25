<?php
declare(strict_types=1);

namespace common\bootstrap;


use App\services\Manticore\IndexService;
use Manticoresearch\Client;
use Yii;
use yii\base\BootstrapInterface;

/**
 * Class SetUp
 * @packaage common\bootstrap
 * @author Aleksey Gusev <audetv@gmail.com>
 */
class SetUp implements BootstrapInterface
{

    public function bootstrap($app)
    {
        $container = Yii::$container;

        $container->setSingleton(IndexService::class, [], [
            new Client($app->params['manticore']),
        ]);
    }
}
