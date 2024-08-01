<?php

/** @var ActiveDataProvider $dataProvider */
/** @var Comment $model */

use frontend\widgets\svodd\TelegramLink;
use App\helpers\TextProcessor;
use App\Question\Entity\Question\Comment;
use frontend\widgets\question\CommentHeader;
use yii\bootstrap5\Html;
use yii\data\ActiveDataProvider;

$position = Yii::$app->request->get()['c'] ?? 0;
$telegramIcon = '<svg class="menu-icon text-svoddRed-100" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="TelegramIcon"><path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.3 3.64 12c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z"></path></svg>';

?>
<div id="<?= $model->position; ?>" data-entity-id="<?= $model->data_id; ?>" class="<?= $position == $model->position ? "card mb-4 border-primary" : "card mb-4" ?>">
    <div class="<?= $position == $model->position ? "card-header d-flex justify-content-between border-primary" : "card-header d-flex justify-content-between" ?>">
        <?= CommentHeader::widget(['model' => $model]); ?>
    </div>
    <div class="card-body">
        <div class="card-text comment-text">
            <?php echo TextProcessor::widget(['text' => $model->text]); ?>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <?= TelegramLink::widget(['comment' => $model]); ?>
        <?php $link = "https://фкт-алтай.рф/qa/question/view-" . $model->question_data_id; ?>
        <?= Html::a(
            '★ Источник',
            $link . "#:~:text=" . $model->datetime->format('H:i d.m.Y'),
            ['target' => '_blank', 'rel' => 'noopener noreferrer']
        ); ?>
    </div>
</div>