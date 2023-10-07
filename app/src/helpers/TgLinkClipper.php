<?php

declare(strict_types=1);

namespace App\helpers;

class TgLinkClipper
{
    public static function process(string $text): string
    {
        if (\Yii::$app->params['cut_telegram_links']) {
            return preg_replace_callback(
                '|(<span class="link">)(.+)(</span>)|iu', function ($matches) {

                if (preg_match("/https:\/\/t.me\/svoddru/", $matches[2], $mm)) {
                    return $matches[1] . $matches[2] . $matches[3];
                }

                if (preg_match("/https:\/\/t.me/", $matches[2], $mm)) {
                    return preg_replace("/https:\/\//", "", $matches[2]);//
                }

                if (preg_match("/http:\/\/t.me/", $matches[2], $mm)) {
                    return preg_replace("/http:\/\//", "", $matches[2]);//
                }

                return $matches[1] . $matches[2] . $matches[3];

            },  $text);
        }

        return $text;
    }
}
