<?php

declare(strict_types=1);

/** @var Comment[] $comments */

use App\models\Comment;
use frontend\widgets\question\card\Header;

$position = Yii::$app->request->get()['c'] ?? 0;

?>
<?php // echo $question->provider->getTotalCount(); ?>
<?php foreach ($comments as $model): ?>

  <div id="<?= $model->position; ?>" class="<?= $position == $model->position ? "card mb-4 border-primary" : "card mb-4"?>">
    <div class="<?= $position == $model->position ? "card-header d-flex justify-content-between border-primary" : "card-header d-flex justify-content-between"?>">
        <?= Header::widget(['model' => $model]); ?>
    </div>
    <div class="card-body">
      <div class="card-text comment-text">
          <?php echo Yii::$app->formatter->asRaw($model->text); ?>
      </div>
    </div>
  </div>

<?php endforeach; ?>


