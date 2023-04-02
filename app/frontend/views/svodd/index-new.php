<?php

declare(strict_types=1);

/** @var QuestionStats[] $list */

/** @var View $this */

use App\Question\Entity\Statistic\QuestionStats;
use App\Svodd\Entity\Chart\Data;
use frontend\widgets\ChartJs\Chart;
use yii\web\JsExpression;
use yii\web\View;

$this->title = 'Хронология обсуждений событий с начала СВОДД';
$this->params['breadcrumbs'][] = $this->title;

/** @var Data[] $data */
$data = Data::find()->orderBy('topic_number DESC')->all();

$labels = [];
$labelLinks = [];
$datasetSvodd = [];
$datasetFct = [];
$dataLabelSvodd = [];
$dataLabelFct = [];

foreach ($data as $item) {

    $labels[] = sprintf("%02d", $item->topic_number) . '. ' . $item->title . ' ' . $item->questionStats->url;
    $labelLinks[] = $item->questionStats->url;
    $dataLabelSvodd[] = $svodd = $item->comments_count;
    $dataLabelFct[] = $fct = $item->end_comment_data_id - $item->start_comment_data_id - $item->comments_count;
    $datasetSvodd[] = $svodd / ($svodd + $fct) * 100;
    $datasetFct[] = $fct / ($svodd + $fct) * 100;
}

$callback = <<<JS
  function(value, index, ticks, test) {   
      if ($(window).width() > 768) {
        return this.getLabelForValue(value);          
      } else {
        console.log(ticks.length - value)
        return ticks.length - value
      }
  }
JS;


?>

<?php echo Chart::widget(
    [
        'type' => 'bar',
        'data' => [
            'labels' => $labels,
            'labelLinks' => $labelLinks,
            'datasets' => [
                [
                    'label' => '# СВОДД',
                    'data' => $datasetSvodd,
                    'dataLabel' => $dataLabelSvodd,
                    'borderWidth' => 1,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                ],
                [
                    'label' => '# ФКТ',
                    'data' => $datasetFct,
                    'dataLabel' => $dataLabelFct,
                    'borderWidth' => 1
                ],
            ]
        ],
        'plugins' => '[ChartDataLabels]',
        'options' => [
            'plugins' => [
                'legend' => [
                    'display' => true
                ],
                'datalabels' => [
                    'color' => '#000000',
                    'labels' => [
                        'title' => [
                            'font' => [
                                'weight' => 400,
                            ],
                        ],
                    ],
                    'formatter' => new JsExpression(<<<JS
                        function(value, context) {
                              if ($(window).width() < 576) {
                                return '';
                              }  
                              if (context.datasetIndex === 0) {
                                return context.chart.data.datasets[0].dataLabel[context.dataIndex];
                              } 
                              if (context.datasetIndex === 1) {
                                return context.chart.data.datasets[1].dataLabel[context.dataIndex];
                              }
                            }
                        JS
                    ),
                ],
            ],
            'animation' => false,
            'layout' => ['padding' => 0],
            'aspectRatio' => 1,
            'indexAxis' => 'y',
            'scales' => [
                'x' => [
                    'stacked' => true,
                    'min' => 0,
                    'max' => 100
                ],
                'y' => [
                    'stacked' => true,
                    'beginAtZero' => true,
                    'ticks' => [
                        'color' => "#000000",
                        'crossAlign' => "far",
                        'callback' => new JsExpression($callback),
                    ],
                ],
            ],
        ],
    ]); ?>

  <div class="row">
    <div class="col-md-12">
      <p class="text-muted">Статистика комментариев на сайте и в темах СВОДД: <a
                href="https://vk.cc/cdjJoJ"
                target="_blank">https://vk.cc/cdjJoJ
        </a>
      </p>
    </div>
  </div>


<?php $js = <<<JS
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

  myChart.canvas.addEventListener('click', (e) => {
    clickableScales(myChart, e)
  })
JS;
