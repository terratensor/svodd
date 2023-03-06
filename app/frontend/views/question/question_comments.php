<?php

declare(strict_types=1);

/** @var ActiveDataProvider $dataProvider */

use App\Question\Entity\Question\Comment;
use frontend\widgets\question\CommentHeader;
use yii\data\ActiveDataProvider;

$position = Yii::$app->request->get()['c'] ?? 0;

?>

<?php /** @var Comment $model */
foreach ($dataProvider->getModels() as $model): ?>
    <div id="<?= $model->position; ?>"
         class="<?= $position == $model->position ? "card mb-4 border-primary" : "card mb-4" ?>">
        <div class="<?= $position == $model->position ? "card-header d-flex justify-content-between border-primary" : "card-header d-flex justify-content-between" ?>">
            <?= CommentHeader::widget(['model' => $model]); ?>
        </div>
        <div class="card-body">
            <div class="card-text comment-text">
                <?php echo Yii::$app->formatter->asRaw(htmlspecialchars_decode($model->text)); ?>
            </div>
        </div>
    </div>
<?php endforeach; ?>
