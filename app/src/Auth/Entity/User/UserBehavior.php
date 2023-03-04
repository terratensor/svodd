<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DateTimeImmutable;
use Exception;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\BaseActiveRecord;

class UserBehavior extends Behavior
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
        /** @var User $model */
        $model = $event->sender;

        $model->setId(new Id($model->getAttribute('id')));
        $model->setDate(new DateTimeImmutable($model->getAttribute('date')));
        $model->setEmail(new Email($model->getAttribute('email')));
        $model->setStatus(new Status($model->getAttribute('status')));
        $model->setRole(new Role($model->getAttribute('role')));
        $model->setPasswordHash($model->getAttribute('password_hash'));
        $model->setJoinConfirmToken(
            $model->getAttribute('join_confirm_token_value') ?
            new Token(
                $model->getAttribute('join_confirm_token_value'),
                new DateTimeImmutable($model->getAttribute('join_confirm_token_expires'))
            ) : null
        );
        $model->setPasswordResetToken(
            $model->getAttribute('password_reset_token_value') ?
            new Token(
                $model->getAttribute('password_reset_token_value'),
                new DateTimeImmutable($model->getAttribute('password_reset_token_expires'))
            ): null
        );
        $model->setNewEmailToken(
            $model->getAttribute('new_email_token_value') ?
            new Token(
                $model->getAttribute('new_email_token_value'),
                new DateTimeImmutable($model->getAttribute('new_email_token_expires'))
            ): null
        );
        $model->setNewEmail(
            $model->getAttribute('new_email') ?
            new Email($model->getAttribute('new_email')) : null
        );
    }

    /**
     * @param Event $event
     */
    public function onBeforeSave(Event $event): void
    {
        /** @var User $model */
        $model = $event->sender;

        $model->setAttribute('id', $model->getId());
        $model->setAttribute('date', $model->getDate()->format('Y-m-d H:i:s'));
        $model->setAttribute('email', $model->getEmail()->getValue());
        $model->setAttribute('status', $model->getStatus()->getName());
        $model->setAttribute('role', $model->getRole()->getName());
        $model->setAttribute('password_hash', $model->getPasswordHash());

        $model->setAttribute('password_reset_token_value', $model->getPasswordResetToken()?->getValue());
        $model->setAttribute(
            'password_reset_token_expires',
            $model->getPasswordResetToken()?->getExpires()->format('Y-m-d H:i:s')
        );
        $model->setAttribute('join_confirm_token_value', $model->getJoinConfirmToken()?->getValue());
        $model->setAttribute(
            'join_confirm_token_expires',
            $model->getJoinConfirmToken()?->getExpires()->format('Y-m-d H:i:s')
        );
        $model->setAttribute('new_email_token_value', $model->getNewEmailToken()?->getValue());
        $model->setAttribute(
            'new_email_token_expires',
            $model->getNewEmailToken()?->getExpires()->format('Y-m-d H:i:s')
        );
    }
}
