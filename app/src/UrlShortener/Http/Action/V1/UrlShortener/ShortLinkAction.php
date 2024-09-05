<?php

declare(strict_types=1);

namespace App\UrlShortener\Http\Action\V1\UrlShortener;

use App\SearchResults\Command\Create\Command as CreateCommand;
use App\SearchResults\Command\Create\Handler as SearchResultHandler;
use App\UrlShortener\Command\Create\Request\Command;
use App\UrlShortener\Command\Create\Request\Handler;
use App\UrlShortener\Form\CreateLink\CreateForm;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;

class ShortLinkAction extends Action
{
    private Handler $handler;
    private SearchResultHandler $searchResultHandler;

    public function __construct(
        $id,
        $controller,
        Handler $handler,
        SearchResultHandler $searchResultHandler,
        $config = []
    ) {
        parent::__construct($id, $controller, $config);
        $this->handler = $handler;
        $this->searchResultHandler = $searchResultHandler;
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function run(): array|string|\yii\httpclient\Response
    {
        $createForm = new CreateForm();

        if ($createForm->load(Yii::$app->request->post()) && $createForm->validate()) {
            $command = new Command();
            $command->origin = $createForm->origin;

            try {
                $response = $this->handler->handle($command);

                if ($user = Yii::$app->user->getIdentity()) {
                    $searchResultCommand = new CreateCommand();
                    $searchResultCommand->user_id = $user->getId();
                    $searchResultCommand->short_link = json_decode($response)->short ?? '';
                    if ($searchResultCommand->short_link) {
                        $this->searchResultHandler->handle($searchResultCommand);
                    }
                }

                return $response;
            } catch (\Throwable $e) {
                Yii::$app->errorHandler->logException($e);
            }
        }

        Yii::$app->response->statusCode = 400;
        return json_encode(['message' => 'Bad Request'], JSON_THROW_ON_ERROR);
    }
}
