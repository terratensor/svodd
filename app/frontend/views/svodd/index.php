<?php

declare(strict_types=1);

/** @var QuestionStats[] $list */

/** @var View $this */

use App\helpers\SessionHelper;
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

    $labels[] = sprintf("%02d", $item->topic_number) . '. ' . $item->title . ' ' . $item->questionStats->url;
    $labelLinks[] = $item->questionStats->url;
    $dataLabelSvodd[] = $svodd = $item->comments_count;
    $dataLabelFct[] = $fct = $item->end_comment_data_id - $item->start_comment_data_id - $item->comments_count;
    if ($key === 0) {
        $current = round($svodd / ($svodd + $fct) * 100, 2);
    }
    $datasetSvodd[] = round($svodd / ($svodd + $fct) * 100, 2);
    $datasetFct[] = round($fct / ($svodd + $fct) * 100, 2);

    $summarySvodd = $summarySvodd + $svodd;
    $summaryFct = $summaryFct + $fct;
}

$summary = round($summarySvodd / ($summarySvodd + $summaryFct) * 100, 2);

$callback = <<<JS
  function (value, index, ticks, test) {   
      if ($(window).width() > 768) {
        return this.getLabelForValue(value);          
      } else {
        return ticks.length - value
      }
  }
JS;

?>
  <div class="py-5">
    <div class="row">
      <div class="col-xl-8">
        <h1 class="bd-title mt-0">СВОДД</h1>
        <p class="bd-lead">Обратная хронология обсуждения событий, статистика комментариев в теме СВОДД и в остальных
          темах ФКТ.</p>
        <div class="d-flex flex-column flex-md-row gap-3">
          <a href="<?= SessionHelper::svoddUrl(Yii::$app->session); ?>"
             class="btn btn-lg bd-btn-lg btn-bd-primary d-flex align-items-center justify-content-center btn-svodd">
            Всё одним потоком
          </a>
        </div>
      </div>
    </div>
  </div>
  <h6>Текущая <?= $current; ?>% Всего <?= $summary; ?>%</h6>
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
            'animations' => false,
            'layout' => [
                'padding' => [
                    'top' => -10,
                ],
            ],
            'plugins' => [
                'legend' => [
                    'title' => [
                        'display' => true,

                    ],
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
                        function (value, context) {
                              // if ($(window).width() < 576) {
                              //   return '';
                              // }  
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
            'maintainAspectRatio' => true,
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
      }
      if($(window).outerWidth() >= 786) {
        document.getElementById('svoddList').classList.remove('show')          
      }
  }
JS;

$this->registerJs($js);
