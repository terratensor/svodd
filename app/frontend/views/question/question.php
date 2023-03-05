<?php

declare(strict_types=1);

/** @var Question $question */

use App\Question\Entity\Question\Question;
use frontend\widgets\question\QuestionHeader;

?>
<div class="collapse" id="collapseQuestion">
  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
        <?= QuestionHeader::widget(['question' => $question]); ?>
    </div>
    <div class="card-body">
      <div class="card-text comment-text">
          <?php echo Yii::$app->formatter->asRaw($question->text); ?>
      </div>
    </div>
  </div>
</div>
