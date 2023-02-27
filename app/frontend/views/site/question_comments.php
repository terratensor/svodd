<?php

declare(strict_types=1);

/** @var Question $question */

use App\models\Question;
use frontend\widgets\question\Card;

?>
<?php foreach ($question->comments as $hit): ?>

  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
        <?= Card::widget(['hit' => $hit]); ?>
    </div>
    <div class="card-body">
      <div class="card-text comment-text">
          <?php echo Yii::$app->formatter->asRaw($hit->getData()['text']); ?>
      </div>
    </div>
  </div>

<?php endforeach; ?>


