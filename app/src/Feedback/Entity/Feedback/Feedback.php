<?php

namespace App\Feedback\Entity\Feedback;

use App\Auth\Entity\User\Id as UserId;
use App\Auth\Entity\User\User;
use App\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @property Id id
 * @property UserId user_id
 * @property string text
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Feedback extends ActiveRecord
{
    private Id $_id;
    private Status $_status;
    private UserId $userId;

    public static function create(Id $id, UserId $user_id, Status $status, string $text): static
    {
        $feedback = new static();
        $feedback->_id = $id;
        $feedback->userId = $user_id;
        $feedback->_status = $status;
        $feedback->text = $text;

        return $feedback;
    }

    public function edit(string $text): void
    {
        $this->text = $text;
    }

    public function isWait(): bool
    {
        return $this->_status->isWait();
    }

    public function isActive(): bool
    {
        return $this->_status->isActive();
    }

    public function isBanned(): bool
    {
        return $this->_status->isBanned();
    }

    public function getId(): Id
    {
        return $this->_id;
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
            FeedbackBehavior::class,
        ];
    }

    public static function tableName(): string
    {
        return '{{%feedback}}';
    }

    /**
     * @param Id $id
     */
    public function setId(Id $id): void
    {
        $this->_id = $id;
    }

    /**
     * @param Status $status
     */
    public function setStatus(Status $status): void
    {
        $this->_status = $status;
    }

    /**
     * @param UserId $userId
     */
    public function setUserId(UserId $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return UserId
     */
    public function getUserId(): UserId
    {
        return $this->userId;
    }

    /**
     * @return Status
     */
    public function getStatus(): Status
    {
        return $this->_status;
    }

    public function isForUser(UserId $userId): bool
    {
        return $this->userId->getValue() === $userId->getValue();
    }
}
