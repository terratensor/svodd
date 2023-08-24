<?php

declare(strict_types=1);

namespace frontend\widgets\question\parser;

use Yii;
use yii\base\Widget;

class CommentViewParser extends Widget
{
    public string $text = '';

    public function run()
    {
        return Yii::$app->formatter->asRaw(htmlspecialchars_decode($this->text));
    }
}
