<?php

declare(strict_types=1);

namespace frontend\controllers;

use App\SearchResults\Http\Action\V1\Collections\IndexAction;

class CollectionsController extends \yii\web\Controller
{

    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
            ],
            // 'view' => [
            //     'class' => ViewAction::class,
            // ],
        ];
    }
}
