<?php

namespace frontend\widgets\search;

use Manticoresearch\ResultHit;
use Yii;
use yii\base\Widget;
use yii\bootstrap5\Html;
use yii\helpers\Url;

class FollowQuestion extends Widget
{
    public ResultHit $hit;
    /**
     * @var array|mixed
     */
    protected mixed $question_id;
    /**
     * @var array|mixed
     */
    private mixed $position;

    public function init()
    {
        $this->question_id = $this->hit->get('parent_id');
        $this->position = $this->hit->get('position');
    }

    public function getUrl(): string
    {
        $total = ceil($this->hit->get('position') / Yii::$app->params['questions']['pageSize']);
        return Url::to(['site/question', 'id' => $this->question_id, 'page' => $total, 'c' =>$this->position, '#' => $this->position]);

    }

    public function run()
    {
        return Html::a(
            'Перейти к вопросу',
            $this->getUrl(),
        );
    }
}
