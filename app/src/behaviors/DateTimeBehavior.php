<?php

declare(strict_types=1);

namespace App\behaviors;

use DateTimeImmutable;
use Exception;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\BaseActiveRecord;

class DateTimeBehavior extends Behavior
{
    /**
     * @return array
     */
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
        $model = $event->sender;

        $model->setDatetime(new DateTimeImmutable($model->getAttribute('date')));
    }

    /**
     * @param Event $event
     */
    public function onBeforeSave(Event $event): void
    {
        $model = $event->sender;

        if (!empty($model->getDatetime())) {
            $model->setAttribute('date', $model->getDatetime()->format('Y-m-d H:i:s'));
        }
    }
}
