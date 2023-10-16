<?php

declare(strict_types=1);

namespace App\helpers;

use Yii;
use yii\base\Widget;

class TextProcessor extends Widget
{
    public string $text = "";

    public function run()
    {
        // Производим конвертацию сломанных в цитате quote кавычек в двойные кавычки
        $text = html_entity_decode(htmlentities(str_replace("&amp;quot;", "&#034;", $this->text)));

        return Yii::$app->formatter->asHtml(TgLinkClipper::process($text));
    }
}
