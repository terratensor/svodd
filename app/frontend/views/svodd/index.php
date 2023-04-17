<?php

declare(strict_types=1);

/** @var Data[] $data */

/** @var View $this */

use App\Svodd\Entity\Chart\Data;
use frontend\widgets\ChartJs\Chart;
use yii\web\JsExpression;
use yii\web\View;


$labels = [];
$labelLinks = [];
$datasetSvodd = [];
$datasetFct = [];
$dataLabelSvodd = [];
$dataLabelFct = [];
$current = 0;
$summary = 0;
$summarySvodd = 0;
$summaryFct = 0;

foreach ($data as $key => $item) {

    $firstPart = sprintf("%02d", $item->topic_number) . ' тема';

    $startDatetime = $item->start_datetime ? Yii::$app->formatter->asDatetime($item->start_datetime, 'php:d.m.y') : '';
    if ($startDatetime) {
        $firstPart .= ' с ' . $startDatetime;
    }

    $secondPart = '';
    if ($key !== 0) {
        $secondPart = $item->end_datetime ? Yii::$app->formatter->asDatetime($item->end_datetime, 'php: по d.m.y') : '';
    }

    $label = $firstPart . $secondPart;
    $labels[] = $label;

    $labelLinks[] = $item->questionStats->url;
    $dataLabelSvodd[] = $svodd = $item->comments_count;
    $dataLabelFct[] = $fct =
        $item->end_comment_data_id
        - $item->start_comment_data_id
        - $item->comments_count
        - $item->comments_delta;

    if ($key === 0) {
        $current = round($svodd / ($svodd + $fct) * 100, 2);
    }

    $datasetSvodd[] = $progress = round($svodd / ($svodd + $fct) * 100, 2);
    $datasetFct[] = round($fct / ($svodd + $fct) * 100, 2);

    $summarySvodd = $summarySvodd + $svodd;
    $summaryFct = $summaryFct + $fct;
}

$summary = round($summarySvodd / ($summarySvodd + $summaryFct) * 100, 2);

$callback = <<<JS
  function callback (value, index, ticks) {
      if ($(window).width() > 340) {
        if (value === 0) {
          return this.getLabelForValue(value) + ' текущая'
        }
        return this.getLabelForValue(value);          
      } else {
        return ticks.length - value
      }
  }
JS;

$this->title = 'Обратная хронология обсуждения СВОДД';

?>
  <h1 class="svodd-title py-3">Обратная хронология обсуждения</h1>
  <div id="svodd-diagram-container">
      <?php echo Chart::widget(
          [
              'id' => 'svoddDiagram',
              'type' => 'bar',
              'data' => [
                  'labels' => $labels,
                  'labelLinks' => $labelLinks,
                  'datasets' => [
                      [
                          'label' => 'СВОДД',
                          'data' => $datasetSvodd,
                          'dataLabel' => $dataLabelSvodd,
                          'borderWidth' => 1,
                          'backgroundColor' => 'rgba(114, 10, 10, 1)',
                          'borderColor' => 'rgba(88, 10, 10, 1)',
                          'datalabels' => [
                              'anchor' => 'end',
                              'clamp ' => false,
                              'align' => 'start',
                              'color' => 'white',
                              'formatter' => new JsExpression(<<<JS
                                (value, context) => {      
                                  return context.chart.data.datasets[0].dataLabel[context.dataIndex];
                                }
                                JS
                              ),
                          ],
                      ],
                      [
                          'label' => 'ФКТ',
                          'data' => $datasetFct,
                          'dataLabel' => $dataLabelFct,
                          'borderWidth' => 1,
                          'backgroundColor' => 'rgba(80, 79, 79, 1)',
                          'borderColor' => 'rgba(54, 52, 52, 1)',
                          'datalabels' => [
                              'anchor' => 'start',
                              'clamp ' => false,
                              'align' => 'end',
                              'color' => 'white',
                              'formatter' => new JsExpression(<<<JS
                                (value, context) => {      
                                  return context.chart.data.datasets[1].dataLabel[context.dataIndex];
                                }
                                JS
                              ),
                          ],
                      ],
                  ]
              ],
              'plugins' => '[ChartDataLabels]',
              'options' => [
                  'animations' => false,
                  'layout' => [
                      'padding' => [
                          'top' => 0,
                      ],
                  ],
                  'plugins' => [
                      'title' => [
                          'display' => true,
                          'text' => "Текущая тема $current% Всего $summary%",
                          'position' => 'top',
                          'align' => 'start',
                          'font' => ['size' => 16, 'weight' => 400],
                          'padding' => 0
                      ],
                      'tooltip' => [
                          'enabled' => true,
                          'callbacks' => [
                              'label' => new JsExpression(<<<JS
                              (context) => {   
                                let value = context.formattedValue;
                                return context.dataset.label+': ' + value + '%'                            
                              }
                            JS
                              )
                          ],
                      ],
                  ],
                  'maintainAspectRatio' => false,
                  //            'aspectRatio' => 1,
                  'indexAxis' => 'y',
                  'scales' => [
                      'x' => [
                          'display' => false,
                          'grid' => [
                              'display' => false,
                          ],
                          'stacked' => true,
                          'min' => 0,
                          'max' => 100
                      ],
                      'y' => [
                          'grace' => '%',
                          'grid' => [
                              'display' => false,
                          ],
                          'stacked' => true,
                          'ticks' => [
                              'color' => new JsExpression(<<<JS
                                () => {
                                const storedTheme = localStorage.getItem('theme')
                                var theme
                                if (storedTheme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                                  theme = 'dark'
                                } else {
                                  if (storedTheme === 'dark') {
                                    theme = 'dark'
                                  } else {
                                    theme = 'light'
                                  }
                                }
                                return (theme === 'dark') ? '#bfc3c3' : '#000000' 
                                }
JS
                              ),
                              'crossAlign' => "far",
                              'callback' => new JsExpression($callback),
                          ],
                      ],
                  ],
              ],
          ]); ?>
  </div>

<?php $js = <<<JS
  updateList();
  $(window).resize(updateList)
  function updateList() {
      if($(window).outerWidth() < 786) {
        // document.getElementById('svoddList').classList.add('show')    
        document.getElementById('svodd-diagram-container').style.height = '80vh'      
      }
      if($(window).outerWidth() >= 786) {
        // document.getElementById('svoddList').classList.remove('show')      
        document.getElementById('svodd-diagram-container').style.height = '80vh'     
      }
      if (screen.availHeight > 1080) {
        document.getElementById('svodd-diagram-container').style.height = '55vh'
      }
      if (screen.availHeight < 700) {
        document.getElementById('svodd-diagram-container').style.height = '115vh'
      }
      if (screen.availHeight <= 600) {
        document.getElementById('svodd-diagram-container').style.height = '110vh'
      }
      if (screen.availHeight <= 500) {
        document.getElementById('svodd-diagram-container').style.height = '150vh'
      }
      if (screen.availHeight < 400) {
        document.getElementById('svodd-diagram-container').style.height = '200vh'
      }
  }
JS;

$this->registerJs($js);
