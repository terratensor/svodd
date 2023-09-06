<?php

declare(strict_types=1);

namespace frontend\widgets;

use Yii;
use yii\base\Widget;

class OgWidget extends Widget
{
    public function init(): void
    {
        $this->view->registerMetaTag(['property' => 'og:site_name', 'content' => 'svodd.ru']);

        $this->view->registerMetaTag(
            [
                'property' => 'og:url',
                'content' => Yii::$app->request->absoluteUrl
            ]);

        $this->view->registerMetaTag(
            ['property' => 'og:type', 'content' => 'website']);

        $this->view->registerMetaTag(
            ['property' => 'og:title', 'content' => $this->view->title]);

        if (key_exists('meta_description', $this->view->params)) {
            $this->view->registerMetaTag(
                [
                    'property' => 'og:description',
                    'content' => $this->view->params['meta_description']
                ]);
        }

        $this->view->registerMetaTag(
            [
                'property' => 'og:image',
                'content' => Yii::$app->params['staticHostInfo'] . '/video/denpobedy.png'
            ]);

        $this->view->registerMetaTag(
            ['property' => 'og:image:width', 'content' => '1920']);

        $this->view->registerMetaTag(
            ['property' => 'og:image:height', 'content' => '480']);
    }
}
