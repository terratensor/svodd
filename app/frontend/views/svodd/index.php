<?php

declare(strict_types=1);

use App\Question\Entity\Question\Comment;
use App\Svodd\Entity\Chart\Data;
use frontend\widgets\ChartJs\Chart;
use yii\web\JsExpression;
use yii\web\View;

/** @var Data[] $data */
/** @var View $this */
/** @var Comment $last_comment */


$this->title = 'Обратная хронология обсуждения СВОДД';
$this->params['meta_description'] = 'График статистики и хронология обсуждения по отдельным темам в обратном хронологическом порядке. Для просмотра вопроса нажмите на заголовок — номер темы.';
$this->registerLinkTag(['rel' => 'canonical', 'href' => Yii::$app->urlManager->createAbsoluteUrl(['svodd/index'])]);

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

    if ($key === 0) {
        //Если это текущая активная тема,
        //то общее количество комментариев равно номеру последнего комментария
        $dataLabelFct[] = $fct =
            max(($last_comment->data_id - $item->end_comment_data_id -
                $item->start_comment_data_id - $item->comments_count - $item->comments_delta), 0);
    } else {
        $dataLabelFct[] = $fct =
            max(($item->end_comment_data_id - $item->start_comment_data_id - $item->comments_count - $item->comments_delta), 0);
    }

    // Fixed FCT-SEARCH-CX [c114e2c19536b12101994a4eb1a9c56f][error][DivisionByZeroError]
    $total = ($svodd + $fct);
    $total = $total === 0 ? 1 : $total;

    if ($key === 0) {
        $current = round($svodd / $total  * 100, 2);
        $current = min($current, 100);
    }

    $datasetSvodd[] = $progress = min(round($svodd / $total  * 100, 2), 100);
    $datasetFct[] = min(round($fct / $total  * 100, 2), 100);

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
                            'color' => '#e1e0de',
                            'formatter' => new JsExpression(
                                <<<JS
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
                            'color' => '#989a9d',
                            'formatter' => new JsExpression(
                                <<<JS
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
                        'padding' => 0,
                    ],
                    'tooltip' => [
                        'enabled' => true,
                        'callbacks' => [
                            'label' => new JsExpression(
                                <<<JS
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
                            'crossAlign' => "far",
                            'callback' => new JsExpression($callback),
                        ],
                    ],
                ],
            ],
        ]
    ); ?>
</div>

<?php $js = <<<JS
  updateList();
  $(window).resize(updateList)
  function updateList() {
      if (screen.availHeight > 1080) {
        document.getElementById('svodd-diagram-container').style.height = '100vh'
      }
      if (screen.availHeight < 700) {
        document.getElementById('svodd-diagram-container').style.height = '140vh'
      }
      if (screen.availHeight <= 600) {
        document.getElementById('svodd-diagram-container').style.height = '160vh'
      }
      if (screen.availHeight <= 500) {
        document.getElementById('svodd-diagram-container').style.height = '190vh'
      }
      if (screen.availHeight < 400) {
        document.getElementById('svodd-diagram-container').style.height = '250vh'
      }
  }
JS;

$this->registerJs($js);
