<?php

/** @var ActiveDataProvider $dataProvider */
/** @var Comment $model */

use App\Question\Entity\Question\Comment;
use frontend\widgets\question\CommentHeader;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;

$position = Yii::$app->request->get()['c'] ?? 0;

?>
<div id="<?= $model->position; ?>" data-entity-id="<?= $model->data_id; ?>"
     class="<?= $position == $model->position ? "card mb-4 border-primary" : "card mb-4" ?>">
  <div class="<?= $position == $model->position ? "card-header d-flex justify-content-between border-primary" : "card-header d-flex justify-content-between" ?>">
      <?= CommentHeader::widget(['model' => $model]); ?>
  </div>
  <div class="card-body">
    <div class="card-text comment-text">
        <?php echo Yii::$app->formatter->asRaw(htmlspecialchars_decode($model->text)); ?>
    </div>
  </div>
  <div class="card-footer d-flex justify-content-end">
      <?php $link = "https://фкт-алтай.рф/qa/question/view-" . $model->question_data_id; ?>
      <?= Html::a(
          $link,
          $link . "#:~:text=" . $model->datetime->format('H:i d.m.Y'),
          ['target' => '_blank', 'rel' => 'noopener noreferrer']
      ); ?>
  </div>
</div>
