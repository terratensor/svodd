<?php

declare(strict_types=1);

namespace frontend\widgets\search;

use App\forms\SearchForm;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use common\models\Badge;


class BadgeFilter extends Widget
{
    public SearchForm $model;

    public function run(): string
    {
        return $this->render('badge-filter', [
            'model' => $this->model
        ]);
    }

    public static function makeUrl(string $currentBadge): array
    {
        $queryParams = Yii::$app->request->getQueryParams();
        $queryParams['search']['badge'] = $currentBadge;

        // If search query is empty, set sort to -datetime
        $searchQuery = $queryParams['search']['query'] ?? '';
        if (empty($searchQuery) && !isset($queryParams['sort'])) {
            $queryParams['sort'] = '-datetime';
        }

        return array_merge(['site/index'], $queryParams);
    }

    private const MATCHING_IN = 'in';

    public const DISABLED_BADGE = "disabled";

    public static function isDisabled(string $currentBadge): bool
    {
        $matching = Yii::$app->request->getQueryParam('search', [])['matching'] ?? '';
        return $matching === self::MATCHING_IN;
    }
}
