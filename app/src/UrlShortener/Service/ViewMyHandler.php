<?php

declare(strict_types=1);

namespace App\UrlShortener\Service;

use yii\httpclient\Client;

class ViewMyHandler
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
    public function handle($short): \yii\httpclient\Response|string
    {
        $response = $this->client
            ->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('get')
            ->setUrl('url-shortener:8000/short?q=' . $short)
            ->send();

        if ($response->isOk) {
            return $response->content;
        }

        return "";
    }
}
