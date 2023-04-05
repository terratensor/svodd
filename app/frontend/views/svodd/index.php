<?php

declare(strict_types=1);

/** @var QuestionStats[] $list */

/** @var View $this */

use App\Question\Entity\Statistic\QuestionStats;
use App\Svodd\Entity\Chart\Data;
use frontend\widgets\ChartJs\Chart;
use frontend\widgets\question\SvoddListWidget;
use yii\web\JsExpression;
use yii\web\View;

/** @var Data[] $data */
$data = Data::find()->orderBy('topic_number DESC')->all();

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

    $firstPart = sprintf("%02d", $item->topic_number) .
        ' тема с ' .
        Yii::$app->formatter->asDatetime($item->start_datetime, 'php:d.m.y');

    $secondPart = $key !== 0 ? Yii::$app->formatter->asDatetime($item->end_datetime, 'php: по d.m.y') : '';

    $label = $firstPart . $secondPart;
    $labels[] = $label;

    $labelLinks[] = $item->questionStats->url;
    $dataLabelSvodd[] = $svodd = $item->comments_count;
    $dataLabelFct[] = $fct = $item->end_comment_data_id - $item->start_comment_data_id - $item->comments_count;

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
  function (value, index, ticks) {
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
                          'label' => '# СВОДД',
                          'data' => $datasetSvodd,
                          'dataLabel' => $dataLabelSvodd,
                          'borderWidth' => 1,
                          'backgroundColor' => 'rgba(54, 162, 235, 0.5)',
                          'borderColor' => 'rgba(54, 162, 235, 1)',
                          'datalabels' => [
                              'anchor' => 'end',
                              'clamp ' => false,
                              'align' => 'start',
                              'color' => 'black',
                              'formatter' => new JsExpression(<<<JS
                                (value, context) => {      
                                  return context.chart.data.datasets[0].dataLabel[context.dataIndex];
                                }
                                JS
                              ),
                          ],
                      ],
                      [
                          'label' => '# ФКТ',
                          'data' => $datasetFct,
                          'dataLabel' => $dataLabelFct,
                          'borderWidth' => 1,
                          'datalabels' => [
                              'anchor' => 'start',
                              'clamp ' => false,
                              'align' => 'end',
                              'color' => 'black',
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
                              'color' => "#000000",
                              'crossAlign' => "far",
                              'callback' => new JsExpression($callback),
                          ],
                      ],
                  ],
              ],
          ]); ?>
  </div>

  <div class="row mt-3">
    <div class="col-sm-12 more-stats">
      <p class="text-muted">Статистика комментариев на сайте и в темах СВОДД: <a
                href="https://vk.cc/cdjJoJ"
                target="_blank">https://vk.cc/cdjJoJ
        </a>
      </p>
    </div>
  </div>

  <div class="svodd-list" id="svoddList">
      <?php echo SvoddListWidget::widget(['models' => $list]); ?>
  </div>

<?php $js = <<<JS
  updateList();
  $(window).resize(updateList)
  function updateList() {
      if($(window).outerWidth() < 786) {
        document.getElementById('svoddList').classList.add('show')    
        document.getElementById('svodd-diagram-container').style.height = '80vh'      
      }
      if($(window).outerWidth() >= 786) {
        document.getElementById('svoddList').classList.remove('show')      
        document.getElementById('svodd-diagram-container').style.height = '80vh'     
      }
      if ($(window).outerHeight() > 1080) {
        document.getElementById('svodd-diagram-container').style.height = '55vh'
      }
      if ($(window).outerHeight() < 700) {
        document.getElementById('svodd-diagram-container').style.height = '115vh'
      }
      if ($(window).outerHeight() <= 600) {
        document.getElementById('svodd-diagram-container').style.height = '110vh'
      }
      if ($(window).outerHeight() <= 500) {
        document.getElementById('svodd-diagram-container').style.height = '150vh'
      }
      if ($(window).outerHeight() < 400) {
        document.getElementById('svodd-diagram-container').style.height = '200vh'
      }
  }
JS;

$this->registerJs($js);
