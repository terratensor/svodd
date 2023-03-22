<?php

namespace App\Rbac\Rules;

use App\Feedback\Entity\Feedback\Feedback;
use yii\rbac\Item;
use yii\rbac\Rule;

class EditFeedback extends Rule
{
    public $name = 'editFeedback';

    /**
     * @param string $user
     * @param Item $item
     * @param array $params
     * @return bool
     */
    public function execute($user, $item, $params): bool
    {
        if(!isset($params['entity'])){
            return false;
        }
        /** @var Feedback $model */
        $model = $params['entity'];

        // Сравниваем строковые значения uuid
        if ($model->user_id === $user)
            return true;
        return false;
    }
}
