<?php

declare(strict_types=1);

namespace frontend\widgets\question\parser;

use App\Question\Entity\Question\Comment;
use DOMDocument;
use GuzzleHttp\Psr7\Response;
use Yii;
use yii\base\Widget;
use yii\httpclient\Client;
use yii\httpclient\Exception;

class CommentViewParser extends Widget
{
    public Comment $model;

    public function run()
    {
        $link_contents = $this->loadDocument();
        $content = $this->getContent($link_contents);
        return Yii::$app->formatter->asRaw(htmlspecialchars_decode($this->model->text . $content));
    }

    private function loadDocument(): array
    {
        $link_content = [];
        $dom = new DOMDocument();
        $dom->loadHTML(mb_convert_encoding($this->model->text, 'html-entities', 'utf-8'));

        $spans = $dom->getElementsByTagName('span');
        /** @var $span \DOMNode */
        foreach ($spans as $key => $span) {
            /** @var \DOMAttr $attribute */
            foreach ($span->attributes as $attribute) {
                if ($attribute->value == 'link') {
                    $content = $this->makeRequestLink($span->nodeValue, $key);
                    if ($content) {
                        $parsed = \ogp\Parser::parse($content);
                        if ($parsed) {
                            $link_content[] = new CommentLinkContent($span->nodeValue, $parsed);
                        }
                    }
                }
            }
        }
        return $link_content;
    }

    private function makeRequestLink(?string $nodeValue, int $key): mixed
    {
        return Yii::$app->cache->getOrSet($this->model->data_id . '_links_' . $key, function () use ($nodeValue) {
            $client = new Client();
            try {
                $response =  $client->createRequest()
                    ->setMethod('GET')
                    ->setUrl($nodeValue)
                    ->send();

                if ($response->statusCode == 200) {
                    return $response;
                }
            } catch (Exception $e) {
                return false;
            }
        });
    }

    private function getContent(array $link_contents): string
    {
        $gird = intdiv(count($link_contents), 3);

        $gird_content = array_slice($link_contents, 0, $gird * 3);
        $rows_content = array_slice($link_contents, $gird * 3, count($link_contents));

        return $this->render(
            "link_content",
            [
                'gird_content' => $gird_content,
                'rows_content' => $rows_content,
            ]
        );
    }
}
