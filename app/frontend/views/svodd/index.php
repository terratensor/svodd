<?php

declare(strict_types=1);

/** @var QuestionStats[] $list */
/** @var View $this */

use App\Question\Entity\Statistic\QuestionStats;
use frontend\widgets\question\SvoddListWidget;
use yii\web\View;

$this->title = 'Хронология обсуждений событий с начала СВОДД';
$this->params['breadcrumbs'][] = $this->title;
?>
  <div>
    <canvas id="myChart"></canvas>
  </div>
<div>
    <?php echo SvoddListWidget::widget(['models' => $list]); ?>
  <div class="row">
    <div class="col-md-12">
      <p class="text-muted">Статистика комментариев на сайте и в темах СВОДД: <a href="https://vk.cc/cdjJoJ" target="_blank">https://vk.cc/cdjJoJ</a>
      </p>
    </div>
  </div>
</div>


<?php $js = <<<JS
var ctx = document.getElementById('myChart');
console.log(ctx)

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['32. Текущая активная тема', '31. 27.03.23', 'Yellow', 'Green', 'Purple', 'Orange', 'Orange'],
      datasets: [{
        label: '# СВОДД',
        data: [105, 694, 957, 781, 720, 819, 1050],
        borderWidth: 1
      }, {
        label: '# ФКТ',
        data: [152, 773, 1567, 1387, 1327, 527, 333],
        borderWidth: 1
      }
      ]
    },
    options: {
      indexAxis: 'y',
      scales: {
        x: {
          stacked: true,
          min: 0,
          max: 1300
        },
        y: {
          stacked: true,
          beginAtZero: false,
          // min: 100,
          // max: 500
        }
      }
    }   
  });
JS;

$this->registerJs($js);
