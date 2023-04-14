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
        $date = new DateTimeImmutable();
        $date = $date->setTimeStamp($timestamp);

        $timezone = YII_ENV === 'prod' ? new DateTimeZone('UTC' ) : $timezone;

        $date = $date->setTimezone($timezone);
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
        $date = $date->setTimezone($timezone);
        return $date->format($format);
    }
}
