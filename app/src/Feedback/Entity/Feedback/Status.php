<?php

namespace App\Feedback\Entity\Feedback;

use Webmozart\Assert\Assert;

class Status
{
    public const WAIT = 'wait';
    public const ACTIVE = 'active';
    public const BANNED = 'banned';

    private string $name;

    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::WAIT,
            self::ACTIVE,
            self::BANNED
        ]);
        $this->name = $name;
    }

    public static function wait(): self
    {
        return new self(self::WAIT);
    }

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function banned(): self
    {
        return new self(self::BANNED);
    }

    public function isWait(): bool
    {
        return $this->name === self::WAIT;
    }

    public function isActive(): bool
    {
        return $this->name === self::ACTIVE;
    }

    public function isBanned(): bool
    {
        return $this->name === self::BANNED;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
