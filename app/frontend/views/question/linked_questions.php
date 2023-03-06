<?php

declare(strict_types=1);

/** @var Question $question */

use App\Question\Entity\Question\Question;

?>
<?php foreach ($question->linkedQuestions as $question): ?>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between">
          <div><?= $question->datetime->format('H:i d.m.Y'); ?>, <?= $question->username; ?></div>
        </div>
        <div class="card-body">
            <div class="card-text comment-text">
                <?php echo Yii::$app->formatter->asRaw($question->text); ?>
            </div>
        </div>
    </div>

<?php endforeach; ?>
