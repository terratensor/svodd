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

        return array_merge(['site/index'], $queryParams);
    }
}
