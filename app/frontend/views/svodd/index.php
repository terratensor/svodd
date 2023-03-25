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
    <?php echo SvoddListWidget::widget(['models' => $list]); ?>
  <div class="row">
    <div class="col-md-12">
      <p class="text-muted">Статистика комментариев на сайте и в темах СВОДД: <a href="https://vk.cc/cdjJoJ" target="_blank">https://vk.cc/cdjJoJ</a>
      </p>
    </div>
  </div>
</div>
<div id="myChart"></div>

<?php $js = <<<JS
const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
      datasets: [{
        label: '# of Votes',
        data: [12, 19, 3, 5, 2, 3],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
JS;

$this->registerJs($js);
