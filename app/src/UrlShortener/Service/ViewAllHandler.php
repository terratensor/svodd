<?php

declare(strict_types=1);

namespace App\UrlShortener\Service;

use yii\httpclient\Client;

class ViewAllHandler
{

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function handle(): \yii\httpclient\Response|string
    {
        $origin = 'origin';

        $response = $this->client
            ->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('get')
            ->setUrl('url-shortener:8000/search')
            ->send();

        if ($response->isOk) {
            return $response->content;
        }

        return "";
    }
}
