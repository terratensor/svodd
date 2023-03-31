<?php

declare(strict_types=1);

/** @var QuestionStats[] $list */

/** @var View $this */

use App\Question\Entity\Statistic\QuestionStats;
use frontend\widgets\question\SvoddListWidget;
use yii\web\View;

$this->title = 'Хронология обсуждений событий с начала СВОДД';
$this->params['breadcrumbs'][] = $this->title;

$js = '';
?>
  <div>
    <canvas id="myChart"></canvas>
  </div>
  <div>
      <?php echo SvoddListWidget::widget(['models' => $list]); ?>

    <div class="row">
      <div class="col-md-5">

          <?php foreach ($list as $key => $item): ?>
            <div class="row">
              <div class="col-md-4 col-sm-12"><?= sprintf("%02d", $item->number) . '. ' . $item->title; ?></div>
              <div class="col-md-8 col-sm-12"><?= $item->url; ?></div>
            </div>
          <?php endforeach; ?>
      </div>
      <div class="col-md-7">
        <canvas id="myChart"></canvas>
      </div>
    </div>
      <?php

      //          $label = $item->chartData->title;
      //          $svodd = $item->chartData->comments_count;
      //          $fct = $item->chartData->end_comment_data_id - $item->chartData->start_comment_data_id - $item->chartData->comments_count;
      //          $dataset = $svodd / ($svodd + $fct) * 100;
      //          $dataset2 = $fct / ($svodd + $fct) * 100;
      //          $js .= drawChart($key+1, $label, $dataset, $dataset2);
      ?>


    <div class="row">
      <div class="col-md-12">
        <p class="text-muted">Статистика комментариев на сайте и в темах СВОДД: <a href="https://vk.cc/cdjJoJ"
                                                                                   target="_blank">https://vk.cc/cdjJoJ</a>
        </p>
      </div>
    </div>
  </div>


<?php
/** @var \App\Svodd\Entity\Chart\Data[] $data */
$data = \App\Svodd\Entity\Chart\Data::find()->all();

//function drawChart($key, $label, $dataset, $dataset2): string {
//return $js = <<<JS
//const ctx{$key} = document.getElementById('myChart-$key');
//new Chart(ctx{$key}, {
//    type: 'bar',
//    data: {
//      labels: ["$label"],
//      datasets: [{
//        label: false,
//        data: [$dataset],
//        borderWidth: 1
//      }, {
//        label: false,
//        data: [$dataset2],
//        borderWidth: 1
//      }
//      ]
//    },
//    options: {
//      aspectRatio: 10,
//      plugins: {
//        legend: {
//          display: false,
//        },
//        labels: {
//          display: false,
//        }
//      },
//      animation: false,
//      indexAxis: 'y',
//      scales: {
//        x: {
//          stacked: true,
//          min: 0,
//          max: 100
//        },
//        y: {
//          stacked: true,
//          beginAtZero: true,
//        }
//      }
//    }
//  });
//JS;
//
//}

$labels = [];
$dataset = [];
$dataset2 = [];
/** @var \App\Svodd\Entity\Chart\Data $item */
foreach ($data as $item) {
    $labels[] = $item->title . ' ' . $item->questionStats->url;
//    $labels[] = $item->topic_number;
    $svodd = $item->comments_count;
    $fct = $item->end_comment_data_id - $item->start_comment_data_id - $item->comments_count;
    $dataset[] = $svodd / ($svodd + $fct) * 100;
    $dataset2[] = $fct / ($svodd + $fct) * 100;

}
$labels = implode('","', $labels);
$dataset = implode(',', $dataset);
$dataset2 = implode(',', $dataset2);

$js = <<<JS
var canvasP = document.getElementById("myChart");
var ctxP = canvasP.getContext('2d');
Chart.defaults.font.size = 16;

var myChart = new Chart(ctxP, {
    plugins: {
      datalabels: {
              color: "#000000"               
            },
    },
    type: 'bar',
    data: {
      labels: ["$labels"],
      datasets: [
          {
            label: '# СВОДД',
            data: [$dataset],
            borderWidth: 1,            
          },
          {
            label: '# ФКТ',
            data: [$dataset2],
            borderWidth: 1
          }
      ],      
    },
    plugins: [ChartDataLabels],
    options: {
      plugins: {
        legend: {
          display: false,
        },
        datalabels: {
          color: "black",
        }
      },
      layout: {
        padding: 0,
      },
      animation: false,
      aspectRatio: 1,
      indexAxis: 'y',
      scales: {
        x: {
          // title: {
          //   align: 'start',          
          // },
          stacked: true,          
          min: 0,
          max: 100
        },
        y: {
          stacked: true,
          beginAtZero: true,
           ticks: {
            color: "#000000",
            crossAlign: "far",
                    // Include a dollar sign in the ticks
                    // callback: function(value, index, ticks) {
                    //     return '$' + this.getLabelForValue(value);
                    // }
                },
        }
      },
    }
  });

  function clickableScales(chart, click) {
    
    const {ctx, canvas, scales: {x,y}} = chart
    
    // Mouse coordinates
    let rect = canvas.getBoundingClientRect()
    console.log(click)
    console.log(rect)
  }

  myChart.canvas.addEventListener('click', (e) => {
    clickableScales(myChart, e)
  })
  
  canvasP.onclick = function(e) {
   var slice = myChart.getElementsAtEventForMode(e, 'nearest', {intersect: true}, true);
   console.log(slice)
   if (!slice.length) return; // return if not clicked on slice
   var label = myChart.data.labels[slice[0].index];
   console.log(label)
   switch (label) {
      // add case for each label/slice
      case 'Värde 5':
         alert('clicked on slice 5');
         window.open('www.example.com/foo');
         break;
      case 'Värde 6':
         alert('clicked on slice 6');
         window.open('www.example.com/bar');
         break;
      // add rests ...
   }
   
//    const points = myChart.getElementsAtEventForMode(evt, 'nearest', { intersect: true }, true);
//
//     if (points.length) {
//         const firstPoint = points[0];
//         const label = myChart.data.labels[firstPoint.index];
//         const value = myChart.data.datasets[firstPoint.datasetIndex].data[firstPoint.index];
//     }
// }
}
  
JS;

$this->registerJs($js);
