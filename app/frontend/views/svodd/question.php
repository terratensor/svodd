<?php

declare(strict_types=1);

/** @var Question $question */
/** @var ActiveDataProvider $dataProvider */

use App\Question\Entity\Question\Question;
use frontend\widgets\question\QuestionHeader;
use yii\data\ActiveDataProvider;

?>
<?php if ($question->linkedQuestions && $dataProvider->sort->attributeOrders['date'] === SORT_DESC): ?>
    <?= $this->render('linked_questions', ['question' => $question]); ?>
<?php endif; ?>

<div data-question-id="<?= $question->data_id; ?>" class="question-view">
  <div class="card mb-4 border-secondary">
    <div class="card-header d-flex justify-content-between border-secondary-subtle">
        <?= QuestionHeader::widget(['question' => $question]); ?>
    </div>
    <div class="card-body">
      <div class="card-text comment-text">
          <?php echo Yii::$app->formatter->asRaw($question->text); ?>
      </div>
    </div>
  </div>
</div>

<?php if ($question->linkedQuestions && $dataProvider->sort->attributeOrders['date'] === SORT_ASC): ?>
    <?= $this->render('linked_questions', ['question' => $question]); ?>
<?php endif; ?>
