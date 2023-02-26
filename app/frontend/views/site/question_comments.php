<?php

declare(strict_types=1);

?>
<?php foreach ($question->comments as $hit): ?>

  <div class="card mb-4">
    <div class="card-header d-flex justify-content-between">
      <div><?= $hit->getData()['datetime']; ?>, <?= $hit->getData()['username']; ?></div>
      <div><?= "#" . $hit->get('data_id'); ?></div>
    </div>
    <div class="card-body">
      <div class="card-text comment-text">
          <?php echo Yii::$app->formatter->asRaw($hit->getData()['text']); ?>
      </div>
    </div>
  </div>

<?php endforeach; ?>


