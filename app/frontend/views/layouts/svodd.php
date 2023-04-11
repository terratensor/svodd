<?php

declare(strict_types=1);

/** @var string $content */


use common\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;

?>
<?php $this->beginContent('@app/views/layouts/layout.php'); ?>
<main role="main" class="flex-shrink-0 svodd">
    <div class="container-fluid">
        <?= Breadcrumbs::widget(
            [
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]
        ) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>
<?php $this->endContent() ?>
