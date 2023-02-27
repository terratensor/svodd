<?php

declare(strict_types=1);

/** @var Question $question */

use App\models\Question;
use frontend\widgets\question\Card;

$position = Yii::$app->request->get()['c'] ?? 0;

?>
<?php foreach ($question->comments as $hit): ?>

  <div id="<?= $hit->get('position'); ?>" class="<?= $position == $hit->get('position') ? "card mb-4 border-primary" : "card mb-4"?>">
    <div class="<?= $position == $hit->get('position') ? "card-header d-flex justify-content-between border-primary" : "card-header d-flex justify-content-between"?>">
        <?= Card::widget(['hit' => $hit]); ?>
    </div>
    <div class="card-body">
      <div class="card-text comment-text">
          <?php echo Yii::$app->formatter->asRaw($hit->getData()['text']); ?>
      </div>
    </div>
  </div>

<?php endforeach; ?>


