<?php

declare(strict_types=1);

namespace frontend\widgets\search;

use App\repositories\Question\QuestionDataProvider;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class TransformQuery extends Widget
{
    public QuestionDataProvider|null $results;

    /**
     * Runs the widget.
     *
     * @return string the result of widget execution to be outputted.
     */
    public function run(): string
    {
        /** @var array<string, mixed> $queryParams */
        $queryParams = Yii::$app->request->queryParams;
        if ($this->results !== null && $this->results->queryTransformed) {

            $queryParams['search']['query'] = $this->results->queryTransformedString;
            $url = array_merge(["site/index"], $queryParams);
            $url = \yii\helpers\Url::to($url);

            $content = 'Добавлены результаты по запросу: ' . Html::a(
                $this->results->queryTransformedString,
                $url,
                [
                    'class' => 'text-decoration-none'
                ]
            );
            return $this->renderCard($this->renderCardBody($content));
        }

        return '';
    }

    public function renderCard(string $content): string
    {
        return Html::tag('div', $content, ['class' => 'card mb-3']);
    }

    public function renderCardBody(string $content): string
    {
        return Html::tag('div', $content, ['class' => 'card-body']);
    }
}
