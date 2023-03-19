<?php

declare(strict_types=1);

/** @var QuestionStats[] $list */

use App\models\QuestionStats;
use frontend\widgets\question\SvoddListWidget;

$this->title = 'Хронология обсуждений событий с начала СВОДД';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="py-4">
    <?php echo SvoddListWidget::widget(['models' => $list]); ?>
</div>
