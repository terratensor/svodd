<?php

namespace frontend\widgets\question;

use Manticoresearch\ResultHit;
use yii\base\Widget;

class Card extends Widget
{
    public ResultHit $hit;
    public function run()
    {
        echo "<div>" . $this->hit->getData()['datetime'] . ", <span class='username'>" . $this->hit->getData()['username'] . "</span></div>";
        echo "<div>#" . $this->hit->get('data_id') . "</div>";
//        echo "<div>#" . $this->hit->get('position') . "</div>";
    }
}
