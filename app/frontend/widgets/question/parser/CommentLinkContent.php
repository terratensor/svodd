<?php

declare(strict_types=1);

namespace frontend\widgets\question\parser;

class CommentLinkContent
{
    public string $site_name = '';
    public string $title = '';
    public string $description = '';
    public string $image = '';
    public string $video = '';
    public string $link_url = '';

    public function __construct(string $link_url, array $parsed)
    {
        if ($og = key_exists("og", $parsed) ? $parsed['og'] : false) {
            $this->site_name = key_exists("og:site_name", $og) ? $og["og:site_name"] : "";
            $this->title = key_exists("og:title", $og) ? $og["og:title"] : "";
            $this->description = key_exists("og:description", $og) ? $og["og:description"] : "";
            $this->image = key_exists("og:image", $og) ? $og["og:image"] : "";
            $this->video = key_exists("og:video:url", $og) ? $og["og:video:url"] : "";
        }
        if ($this->title == '') {
            $this->title = key_exists("title", $parsed) ? $parsed["title"] : "";
        }
        if ($this->description == '') {
            $this->description = key_exists("description", $parsed) ? $parsed["description"] : "";
        }
        $this->link_url = $link_url;
    }
}
