<?php

namespace App\User\Profile\Entity;

use App\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * @property Id|string $id
 * @property string|null $name
 * @property string|null $lastname
 * @property int created_at
 * @property int updated_at
 */
class Profile extends ActiveRecord
{
    public static function create(Id $id, ?string $name, ?string $lastname): self
    {
        $profile = new static();
        $profile->id = $id;
        $profile->name = $name;
        $profile->lastname = $lastname;

        return $profile;
    }

    public function edit(?string $name, ?string $lastname): void
    {
        $this->name = $name;
        $this->lastname = $lastname;
    }

    public function behaviors(): array
    {
        return [
            TimestampBehavior::class,
        ];
    }

    public static function tableName(): string
    {
        return '{{%user_profiles}}';
    }
}
