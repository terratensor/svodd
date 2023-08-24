<?php

declare(strict_types=1);

use frontend\widgets\question\parser\CommentLinkContent;

/** @var $gird_content CommentLinkContent[] */
/** @var $rows_content CommentLinkContent[] */

?>

<div class="mt-4" xmlns="http://www.w3.org/1999/html">
    <?php if ($gird_content): ?>
      <div class="row row-cols-1 row-cols-md-3 g-4">
          <?php foreach ($gird_content as $content): ?>
            <div class="col">
              <div class="card">
                  <?php if ($content->video): ?>
                    <div class="ratio ratio-16x9">
                      <iframe src="<?= $content->video; ?>" allowfullscreen></iframe>
                    </div>
                  <?php elseif ($content->image): ?>
                    <img src="<?= $content->image ?>" class="card-img-top" alt="<?= $content->title ?>">
                  <?php endif; ?>
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="<?= $content->link_url; ?>" target="_blank" rel="noreferrer noopener nofollow">
                        <?= $content->title ?>
                    </a></h5>
                  <p class="card-text"><?= $content->description ?></p>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <?php foreach ($rows_content as $row): ?>
      <div class="card mb-3 mt-3">
        <div class="row g-0">
            <?php if ($row->video): ?>
              <div class="col-sm-3">
                <div class="ratio ratio-16x9">
                  <iframe src="<?= $row->video; ?>"></iframe>
                </div>
              </div>
            <?php elseif ($row->image): ?>
              <div class="col-sm-3">
                <img src="<?= $row->image ?>" class="img-fluid rounded-start object-fit-contain" alt="<?= $row->title ?>">
              </div>
            <?php endif; ?>
          <div class="col-sm-9">
            <div class="card-body">
              <h5 class="card-title"><a href="<?= $row->link_url; ?>" target="_blank"
                                        rel="noreferrer noopener nofollow"><?= $row->title ?></a></h5>
              <p class="card-text"><?= $row->description; ?></p>
              <p class="card-text"><small class="text-body-secondary"><?= $row->site_name; ?></small></p>
            </div>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
</div>
