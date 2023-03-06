<?php

declare(strict_types=1);

namespace frontend\widgets\question;

use App\Question\Entity\Question\Question;
use yii\base\Widget;

class QuestionHeader extends Widget
{
    public Question $question;

    public function run()
    {
        echo "<div>" . $this->question->getDatetime()->format('H:i d.m.Y')
            . ", <span class='username'>"
            . $this->question->username
            . "</span></div>";

        echo "<div>#"
            . $this->question->data_id
            . "</div>";
    }
}
