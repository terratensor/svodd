<?php

namespace frontend\widgets\question;

use App\models\ListItem;
use yii\base\Widget;
use yii\helpers\Html;

class SvoddListWidget extends Widget
{
    /**
     * @var ListItem[]
     */
    private array $models;

    public function init()
    {
        $config = \Yii::getAlias('@webroot') . "/list/config.json";

        $file = $this->readJsonFile($config);
        $doc = json_decode($file, true, 512, JSON_THROW_ON_ERROR);
        $models = [];
        foreach ($doc['list'] as $item) {
            $models[] = new ListItem($item);
        }

        $this->models = $models;
    }

    public function run()
    {
        $links = '';
        foreach ($this->models as $model) {

            $title = $model->date;
            if ($title === '') {
                $title = 'Текущая активная тема';
            }
            $item2 = Html::tag('h5', $model->num . '. ' . $title) . $model->url;
            $item1 = Html::tag('div', $item2, ['class' => 'ms-2 me-auto']) .
             Html::tag('span', $model->comments, ['class' => 'badge bg-primary rounded-pill']);
            $item = Html::tag('div', $item1, ['class' => 'd-flex w-100 justify-content-between align-items-start']);
            $link = Html::a($item, ['site/question', 'id' => $model->id, 'page' => 1], ['class' => 'list-group-item list-group-item-action']);

            $links .= $link;
        }

        return Html::tag('div', $links, ['class' => 'list-group']);
    }

    private function readJsonFile($config): bool|string
    {
        return file_get_contents($config);
    }
}

