<?php

declare(strict_types=1);

namespace common\auth;

use App\Auth\Entity\User\Id;
use App\Auth\Entity\User\UserRepository;
use DomainException;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\di\NotInstantiableException;
use yii\web\IdentityInterface;
use App\Auth\Entity\User\User;

class Identity implements IdentityInterface
{
    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @throws NotInstantiableException
     * @throws InvalidConfigException
     */
    public static function findIdentity($id): ?Identity
    {
        try {
            $user = self::getRepository()->get(new Id($id));
        } catch (DomainException $e) {
            return null;
        }
        return new self($user);
    }

    /**
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null): ?IdentityInterface
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId(): string
    {
        return $this->user->getId()->getValue();
    }

    public function getEmail(): string
    {
        return $this->user->getEmail()->getValue();
    }

    public function getAuthKey(): string
    {
        return $this->user->getAuthKey()->getValue();
    }

    public function validateAuthKey($authKey): bool
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @throws NotInstantiableException
     * @throws InvalidConfigException
     */
    private static function getRepository(): UserRepository
    {
        return Yii::$container->get(UserRepository::class);
    }
}
