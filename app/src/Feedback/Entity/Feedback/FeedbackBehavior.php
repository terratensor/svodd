<?php

namespace App\Feedback\Entity\Feedback;

use Exception;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\BaseActiveRecord;
use \App\Auth\Entity\User\Id as UserId;

class FeedbackBehavior extends Behavior
{
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_FIND => 'onAfterFind',
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'onBeforeSave',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'onBeforeSave',
        ];
    }

    /**
     * @param Event $event
     * @throws Exception
     */
    public function onAfterFind(Event $event): void
    {
        /** @var Feedback $model */
        $model = $event->sender;

        $model->setId(new Id($model->getAttribute('id')));
        $model->setUserId(new UserId($model->getAttribute('user_id')));
        $model->setStatus(new Status($model->getAttribute('status')));
    }

    public function onBeforeSave(Event $event): void
    {
        /** @var Feedback $model */
        $model = $event->sender;

        $model->setAttribute('id', $model->getId());
        $model->setAttribute('user_id', $model->getUserId());
        $model->setAttribute('status', $model->getStatus()->getName());
    }
}
