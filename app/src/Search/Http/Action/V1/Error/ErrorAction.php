<?php

declare(strict_types=1);

namespace App\Search\Http\Action\V1\Error;

use yii\httpclient\Client;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\web\Response;

class ErrorAction extends \yii\web\ErrorAction
{
    /**
     * @return Response|string
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function run(): \yii\web\Response|string
    {
        $host = \Yii::$app->params['urlShortenerHost'];
        $client = new Client(
            [
                'transport' => 'yii\httpclient\CurlTransport'
            ]
        );

        if (\Yii::$app->getResponse()->statusCode === 404) {
            $path = \Yii::$app->request->getPathInfo();

            $searchUrl = $host . "/short?q=" . $path;

            $response = $client->createRequest()
                ->setMethod('get')
                ->setUrl($searchUrl)
                ->send();

            if ($response->statusCode == 200) {
                $data = json_decode($response->content);
                if (!empty($data)) {
                    $redirectUrl = \Yii::$app->params['urlShortenerUrl'] . "/redirect?s=" . $path;
                    return $this->controller->redirect($redirectUrl);
                }
            }
        }
        return parent::run();
    }
}
