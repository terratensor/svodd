<?php

declare(strict_types=1);

namespace App\Search\Http\Action\V1\Error;

use yii\httpclient\Client;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\web\Response;
use function PHPUnit\Framework\isEmpty;

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
            
            $path = $this->processPath(\Yii::$app->request->getPathInfo());
            
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

    /**
     * Если в адресе есть пробел и звезда, то вырезаем их из адреса
     * и возвращаем только код ссылки из 8 знаков в matches[1]
     * @param string $getPathInfo
     * @return string
     */
    private function processPath(string $getPathInfo): string
    {
       preg_match('/([^\W_]{8,})(\s★)/imu', $getPathInfo, $matches);
       if (!$matches) {
           return $getPathInfo;
       }
       return $matches[1];
    }
}
