<?php

declare(strict_types=1);

namespace App\UrlShortener\Command\Create\Request;

use yii\base\InvalidConfigException;
use yii\httpclient\Client;
use yii\httpclient\Exception;

class Handler
{
    private Client $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function handler(Command $command)
    {
        $origin = $command->origin;

        $response = $this->client
            ->createRequest()
            ->setMethod('post')
            ->setUrl('url-shortener:8000/create')
            ->setData(['origin' => $origin])
            ->send();

        if ($response->isOk) {
            $data = $response->data;
            var_dump($data);
        }
    }
}
