<?php

declare(strict_types=1);

namespace frontend\widgets\search;

use App\repositories\Question\QuestionDataProvider;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class SuggestQuery extends Widget
{

    public QuestionDataProvider|null $results;

    /**
     * This method is the main entry point of the widget.
     * It checks if there are no search results and a suggested query string is available.
     * If so, it generates a URL with the suggested query and returns a rendered card with a link to the suggested query.
     * Otherwise, it returns an empty string.
     * @return string the result of widget execution to be outputted.
     */

    public function run(): string
    {
        /** @var array<string, mixed> $queryParams */
        $queryParams = Yii::$app->request->queryParams;
        if ($this->results !== null && $this->results->getTotalCount() === 0 && $this->results->suggestQueryString) {

            $queryParams['search']['query'] = $this->results->suggestQueryString;
            $url = array_merge(["site/index"], $queryParams);
            $url = \yii\helpers\Url::to($url);

            $content = 'Возможно, вы имели ввиду: <strong>' . Html::a(
                $this->results->suggestQueryString,
                $url,
                [
                    'class' => 'text-decoration-none'
                ]
            ) . '</strong>';
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
