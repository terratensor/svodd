<?php

declare(strict_types=1);

namespace frontend\widgets\ChartJs;

use yii\base\Widget;
use yii\helpers\Html;

class Chart extends Widget
{
    public $id;
    public $height;
    public $width;
    public $type;
    public $data;
    public $options;
    public $setTooltip = true;

    public $duration = 100;

    private $animation;
    private $tooltips;

    public function init()
    {
        parent::init();
        if(empty($this->id))
            $this->id = uniqid('chart');

        $this->getTooltips();
        $this->getAnimation();

        echo Html::beginTag('canvas',[
            'id' => $this->id,
                ($this->height) ?? 'height' => $this->height,
                ($this->width) ?? 'width' => $this->width,
        ]);
        echo Html::endTag('canvas');
    }

    public function run()
    {
        ChartAssets::register($this->getView());
        return $this->renderChart();
    }
}
