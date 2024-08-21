<?php

declare(strict_types=1);

/** @var \yii\web\View $this */

/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppFeatureAsset;
use frontend\widgets\MaintenanceWidget;
use frontend\widgets\search\ShortLinkModal;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

AppFeatureAsset::register($this);
\frontend\widgets\OgWidget::widget();

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerJsFile('/js/color-mode-toggler.js', ['position' => \yii\web\View::POS_HEAD]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100" data-bs-theme="light">

<head>
    <?= $this->render('favicon'); ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <?= $this->render('yandex_metrika'); ?>
</head>

<body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <?= $this->render('red_header'); ?>

    <main role="main" class="flex-shrink-0 mb-3">
        <div class="container-fluid pb-0">
            <?php MaintenanceWidget::widget(); ?>
            <?= Alert::widget() ?>
        </div>

        <?= $content ?>

    </main>

    <?= $this->render('footer'); ?>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage();
