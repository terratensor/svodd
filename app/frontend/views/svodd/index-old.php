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

\frontend\assets\ChartJs\ChartJsAsset::register($this);
?>
  <div>
    <canvas id="myChart"></canvas>
  </div>
  <div>
      <?php echo SvoddListWidget::widget(['models' => $list]); ?>

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
$data = \App\Svodd\Entity\Chart\Data::find()->orderBy('topic_number DESC')->all();

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
$labelLinks = [];
$dataset = [];
$dataset2 = [];
$dataLabel1 = [];
$dataLabel2 = [];
/** @var \App\Svodd\Entity\Chart\Data $item */
foreach ($data as $item) {
    $labels[] = sprintf("%02d", $item->topic_number). '. ' . $item->title . ' ' . $item->questionStats->url;
    $labelLinks[] = $item->questionStats->url;
//    $labels[] = $item->topic_number;
    $dataLabel1[] = $svodd = $item->comments_count;
    $dataLabel2[] = $fct = $item->end_comment_data_id - $item->start_comment_data_id - $item->comments_count;
    $dataset[] = $svodd / ($svodd + $fct) * 100;
    $dataset2[] = $fct / ($svodd + $fct) * 100;

}
$labels = implode('","', $labels);
$labelLinks = implode('","', $labelLinks);
$dataset = implode(',', $dataset);
$dataset2 = implode(',', $dataset2);
$dataLabel1 = implode(',', $dataLabel1);
$dataLabel2 = implode(',', $dataLabel2);

$js = <<<JS
var canvasP = document.getElementById("myChart");
var ctxP = canvasP.getContext('2d');
Chart.defaults.font.size = 16;

console.log(Chart.register)

var myChart = new Chart(ctxP, {
    type: 'bar',
    data: {
      labels: ["$labels"],
      labelLinks: ["$labelLinks"],
      datasets: [
          {
            label: '# СВОДД',
            data: [$dataset],
            dataLabel: [$dataLabel1],
            borderWidth: 1,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',                     
            borderColor: 'rgba(54, 162, 235, 1)',
          },
          {
            label: '# ФКТ',
            data: [$dataset2],
            dataLabel: [$dataLabel2],
            borderWidth: 1
          }
      ],      
    },
    plugins: [ChartDataLabels],
    options: {
      plugins: {
        legend: {
          display: true,
        },
        datalabels: {
          color: "black",
           labels: {
              title: {
                font: {
                  weight: '400'
                }
              },
            },
          // align: "right",
          // offset: 100,
           formatter: function(value, context) {
              
              // console.log(context)
              if (context.datasetIndex === 0) {
                return context.chart.data.datasets[0].dataLabel[context.dataIndex];
              } 
              if (context.datasetIndex === 1) {
                return context.chart.data.datasets[1].dataLabel[context.dataIndex];
              }
              // console.log(context.chart.data.datasets[0].dataLabel[context.dataIndex])
            },
        },      
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
        // console.log(i)
        // console.log(chart.data.labelLinks[i])
        window.open(chart.data.labelLinks[i])
        // ctx.fillStyle = 'grey'
        // ctx.rect(left, top + (height * i), right, height)
        // ctx.fill()
      }
    }
    
    // console.log(y.height)
    // console.log(y.ticks.length)
    // console.log(height)
   
  }

  myChart.canvas.addEventListener('click', (e) => {
    clickableScales(myChart, e)
  })
  
  canvasP.onclick = function(e) {
   var slice = myChart.getElementsAtEventForMode(e, 'nearest', {intersect: true}, true);
   // console.log(slice)
   if (!slice.length) return; // return if not clicked on slice
   var label = myChart.data.labels[slice[0].index];
   // console.log(label)
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
