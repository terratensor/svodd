<?php

declare(strict_types=1);

/** @var QuestionView $question */

use App\helpers\DateHelper;
use App\helpers\TextProcessor;
use App\models\QuestionView;

?>
<?php foreach ($question->linkedQuestions as $hit): ?>

  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
      <div><?= DateHelper::showDateFromTimestamp($hit->getData()['datetime']); ?>, <?= $hit->getData()['username']; ?></div>
    </div>
    <div class="card-body">
      <div class="card-text comment-text">
          <?php echo TextProcessor::widget(['text' => $hit->getData()['text']]); ?>
      </div>
    </div>
  </div>

<?php endforeach; ?>

