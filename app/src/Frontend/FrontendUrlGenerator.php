<?php

declare(strict_types=1);

namespace App\Frontend;

class FrontendUrlGenerator
{
    private string $baseurl;

    public function __construct(string $baseurl)
    {
        $this->baseurl = $baseurl;
    }

    public function generate(string $uri, array $params = []): string
    {
        return $this->baseurl
            . ($uri ? '/' . $uri : '')
            . ($params ? '?' . http_build_query($params) : '');
    }
}
