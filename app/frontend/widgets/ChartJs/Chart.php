<?php

declare(strict_types=1);

namespace frontend\widgets\ChartJs;

use frontend\assets\ChartJs\ChartJsAsset;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

class Chart extends Widget
{
    public string $id;
    public int|null $height = null;
    public int|null $width = null;
    public string $type;
    public array $data;
    public array $options;
    public string $plugins;

    public function init()
    {
        parent::init();
        if (empty($this->id)) {
            $this->id = uniqid('chart');
        }

        echo Html::beginTag('canvas', [
            'id' => $this->id,
                ($this->height) ?? 'height' => $this->height,
                ($this->width) ?? 'width' => $this->width,
        ]);

        echo Html::endTag('canvas');
    }

    public function run()
    {
        ChartJsAsset::register($this->getView());
        $this->renderChart();
    }

    private function renderChart(): void
    {
        $data = Json::encode($this->data);
        $type = Json::encode($this->type);
        $options = Json::encode($this->options);
        $plugins = $this->plugins;

        $this->view->registerJs("
            var canvasP = document.getElementById(\"$this->id\");
            var ctxP = canvasP.getContext(\"2d\");
            Chart.defaults.font.size = 16;
            var $this->id = new Chart(ctxP, {
                type: $type,
                data: $data,
                plugins: $plugins,
                options: $options,
            });
        " . $this->getAdditionalJs());
    }

    private function getAdditionalJs(): string
    {
        return <<<JS
  function clickableScales(chart, click) {
    const { ctx, canvas, scales: { x, y } } = chart
    const top = y.top
    const left = y.left
    const right = y.right
    const bottom = y.bottom
    const height = y.height / y.ticks.length
    
    // Mouse coordinates
    let rect = canvas.getBoundingClientRect()    
    const xCoor = click.clientX - rect.left
    const yCoor = click.clientY - rect.top    
    
    for (let i = 0; i < y.ticks.length; i++) {
      if (xCoor >= left && xCoor <= right && yCoor >= top + (height * i) && yCoor <= top + height + (height * i)) {
        window.open(chart.data.labelLinks[i])
      }
    }
  }

  $this->id.canvas.addEventListener('click', (e) => {
    clickableScales($this->id, e)
  });
JS;
    }
}
