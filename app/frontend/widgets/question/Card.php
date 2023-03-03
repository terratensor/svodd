<?php

namespace frontend\widgets\question;

use App\helpers\DateHelper;
use Manticoresearch\ResultHit;
use yii\base\Widget;

class Card extends Widget
{
    public ResultHit $hit;

    public function run()
    {
        echo "<div>" . DateHelper::showDateFromTimestamp($this->hit->getData()['datetime'])
            . ", <span class='username'>"
            . $this->hit->getData()['username']
            . "</span></div>";

        echo "<div>#"
            . $this->hit->get('data_id')
            . "</div>";
    }
}
