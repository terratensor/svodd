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
 
    document.querySelectorAll('[data-bs-theme-value]')
      .forEach(toggle => {
        toggle.addEventListener('click', () => {
          const theme = toggle.getAttribute('data-bs-theme-value')
          console.log(theme)          
          setTheme(theme)          
        })
      })

    function setTheme(theme) {
      var chart = $this->id
      const x = chart.config.options.scales.x
      const y = chart.config.options.scales.y
      var value
         if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
              value = 'dark'
         } else {
              if (theme === 'dark') {
                value = 'dark'
              } else {
                value = 'light'
              }
         }
           
         if (theme === 'dark') {
              // настройки для темной темы, цвет текста
              Chart.defaults.color = 'rgba(191, 195, 195, 1)';
              // цвет текста заголовка оси y, номер темы
              y.ticks.color = '#bfc3c3'    
              // цвет dataset СВОДД, горизонтальные линии - красный
              chart.config.data.datasets[0].backgroundColor = 'rgba(114, 10, 10, 1)'
              chart.config.data.datasets[0].borderColor = 'rgba(88, 10, 10, 1)'
              // цвет dataset ФКТ, горизонтальные линии - серый
              chart.config.data.datasets[1].backgroundColor = 'rgba(80, 79, 79, 1)'
              chart.config.data.datasets[1].borderColor = 'rgba(54, 52, 52, 1)'
         } else {
              // настройки для светлой темы, цвет текста
              Chart.defaults.color = '#212529';
              // цвет текста заголовка оси y, номер темы
              y.ticks.color = '#212529'
              // цвет dataset СВОДД, горизонтальные линии - красный
              chart.config.data.datasets[0].backgroundColor = 'rgba(114, 10, 10, 1)'
              chart.config.data.datasets[0].borderColor = 'rgba(88, 10, 10, 1)'
              // цвет dataset ФКТ, горизонтальные линии - серый
              chart.config.data.datasets[1].backgroundColor = 'rgba(80, 79, 79, 1)'
              chart.config.data.datasets[1].borderColor = 'rgba(54, 52, 52, 1)'
         }           
       
      chart.update()
    }
  
  const theme = localStorage.getItem('theme')
  setTheme(theme);
  
  responsiveFonts();

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
        const newWindow = window.open(chart.data.labelLinks[i], "_blank", "noopener,noreferrer",)
        if (newWindow) newWindow.opener = null        
      }
    }
  }

  $this->id.canvas.addEventListener('click', (e) => {
    clickableScales($this->id, e)
  });
  
  function pointerScales(chart, e) {
    const { canvas, scales: { y } } = chart
    const top = y.top
    const left = y.left
    const right = y.right
    const bottom = y.bottom

    // Mouse coordinates
    let rect = canvas.getBoundingClientRect()    
    const xCoor = e.clientX - rect.left
    const yCoor = e.clientY - rect.top   
    
    if (xCoor >= left && xCoor <= right && yCoor > top && yCoor < bottom) {
      e.target.style.cursor = 'pointer'  
    } else {
      e.target.style.cursor = 'default'
    }
  }
  
  $this->id.canvas.addEventListener('mousemove', (e) => {
    pointerScales($this->id, e)
  });

  $(window).resize(responsiveFonts)
  function responsiveFonts() {
      if($(window).outerWidth() > 999) {
        Chart.defaults.font.size = 14;  
      }
      if(  $(window).outerWidth() > 500 && $(window).outerWidth() < 999) {
        Chart.defaults.font.size = 14;
      }
      if(  $(window).outerWidth() > 500 && $(window).outerWidth() < 770) {
        Chart.defaults.font.size = 14;
      }
      if($(window).outerWidth() < 500) {
        Chart.defaults.font.size = 11;
      }
      if ($(window).outerHeight() < 860) {
        Chart.defaults.font.size = 12;
      }
      if(screen.availHeight > screen.availWidth){
        Chart.defaults.font.size = 11;
      }
      $this->id.update();
  }
JS;
    }
}
