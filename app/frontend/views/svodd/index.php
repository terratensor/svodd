<?php

declare(strict_types=1);

/** @var QuestionStats[] $list */

use App\Question\Entity\Statistic\QuestionStats;
use frontend\widgets\question\SvoddListWidget;

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
