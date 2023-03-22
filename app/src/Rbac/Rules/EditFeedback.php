<?php

namespace App\Rbac\Rules;

use App\Feedback\Entity\Feedback\Feedback;
use DateTimeImmutable;
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
     * @throws \Exception
     */
    public function execute($user, $item, $params): bool
    {
        if(!isset($params['entity'])){
            return false;
        }
        /** @var Feedback $model */
        $model = $params['entity'];

        // Сравниваем строковые значения uuid
        if (!$model->user_id === $user) {
            return false;
        }

        if (!$model->allowedToEdit(new DateTimeImmutable())) {
            return false;
        }

        return true;
    }
}
