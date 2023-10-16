<?php

declare(strict_types=1);

namespace App\helpers;

use DOMAttr;
use DOMDocument;
use DOMNode;

class TgLinkClipper
{
    public static function process(string $text): string
    {
        if (\Yii::$app->params['cut_telegram_links']) {

            $doc = new DOMDocument('1.0', 'UTF-8');
            // With a DOMDocument object, you should be able to place an @ before the load method in order to SUPPRESS all WARNINGS.
            @$doc->loadHTML(mb_convert_encoding($text, 'HTML-ENTITIES', 'UTF-8'));

            $nodes = $doc->getElementsByTagName('span');

            /** @var DOMNode $node */
            foreach ($nodes as $node) {
                self::parseNode($node);
            }

            return $doc->saveHTML();
        }

        return $text;
    }

    private static function parseNode(DOMNode $node): void
    {
        /** @var DOMAttr $attribute */
        foreach ($node->attributes as $attribute) {
            $value = $node->nodeValue;
            if ($attribute->name == 'class' && $attribute->value === 'link') {
                var_dump("true");
                if (preg_match("/https:\/\/t.me\/svoddru/", $value, $mm)) {
                    break;
                }
                if (preg_match("/https:\/\/t.me/", $value, $mm)) {
                    $attribute->value = 'no-link';
                    break;
                }
                if (preg_match("/http:\/\/t.me/", $value, $mm)) {
                    $attribute->value = 'no-link';
                    break;
                }
            }
        }
    }
}
