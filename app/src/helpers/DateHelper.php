<?php

namespace App\helpers;

use DateTimeImmutable;
use DateTimeZone;
use Exception;

class DateHelper
{
    public static function showDateFromTimestamp(
        int $timestamp,
        DateTimeZone $timezone = new DateTimeZone('Europe/Moscow'),
        string $format = 'H:i d.m.Y'): string
    {
        $date = new DateTimeImmutable("@$timestamp");
        return $date->format($format);
    }

    /**
     * @throws Exception
     */
    public static function showDateFromString(
        ?string $date,
        DateTimeZone $timezone = new DateTimeZone('Europe/Moscow'),
        string $format = 'd.m.Y'): string
    {
        $date = new DateTimeImmutable($date);
        return $date->format($format);
    }
}
