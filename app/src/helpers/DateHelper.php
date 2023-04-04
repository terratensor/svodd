<?php

namespace App\helpers;

use DateTimeImmutable;
use DateTimeZone;

class DateHelper
{
    public static function showDateFromTimestamp(
        int $timestamp,
        DateTimeZone $timezone = new DateTimeZone('Europe/Moscow'),
        string $format = 'H:i d.m.Y'): string
    {
        $date = new DateTimeImmutable();
        $date = $date->setTimezone($timezone);
        $date = $date->setTimeStamp($timestamp);
        return $date->format($format);
    }
}
