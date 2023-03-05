<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use DateTimeImmutable;
use DomainException;
use Webmozart\Assert\Assert;

class Token
{
    private string $value;
    private DateTimeImmutable $expires;

    public function __construct(string $value, DateTimeImmutable $expires)
    {
        Assert::uuid($value);
        $this->value = mb_strtolower($value);
        $this->expires = $expires;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getExpires(): DateTimeImmutable
    {
        return $this->expires;
    }

    public function validate(string $value, DateTimeImmutable $date): void
    {
        if (!$this->isEqualTo($value)) {
            throw new DomainException('Токен недействителен.');
        }
        if ($this->isExpiredTo($date)) {
            throw new DomainException('Срок действия токена истек.');
        }
    }

    public function validateUpdate(DateTimeImmutable $date): void
    {
        if ( !$this->isExpiredTo($date)) {
            $expires = $this->getExpires()->modify('-55 min');

            if ($date < $expires) {
                $diff = $date->diff($expires);
                $value = $diff->format('%I:%S');
                throw new DomainException("Повторная отправка будет возможна через $value");
            }
        }
    }

    private function isEqualTo(string $value): bool
    {
        return $this->value === $value;
    }

    public function isExpiredTo(DateTimeImmutable $date): bool
    {
        return $this->expires <= $date;
    }

    public function isEmpty(): bool
    {
        return empty($this->value);
    }
}
