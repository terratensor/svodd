<?php

declare(strict_types=1);

/** @var ActiveDataProvider $dataProvider */

use App\Question\Entity\Question\Comment;
use frontend\widgets\question\CommentHeader;
use frontend\widgets\Scroll\ScrollWidget;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;

$position = Yii::$app->request->get()['c'] ?? 0;

?>

<?php /** @var Comment $model */
foreach ($dataProvider->getModels() as $model): ?>
    <?php $data_entity_id = $model->data_id; ?>
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
        <?php
        var_dump($model->datetime->getTimestamp());
        echo "<br>";
        var_dump($model->datetime);
        ?>
      <div class="card-footer d-flex justify-content-end">
          <?php $link = "https://фкт-алтай.рф/qa/question/view-" . $model->question_data_id; ?>
          <?= Html::a(
              'Перейти к комментарию на ФКТ',
              $link . "#:~:text=" . $model->datetime->format('H:i d.m.Y'),
              ['target' => '_blank', 'rel' => 'noopener noreferrer']
          ); ?>
      </div>
    </div>
<?php endforeach; ?>
<?= ScrollWidget::widget(['data_entity_id' => $data_entity_id ?? 0]); ?>
