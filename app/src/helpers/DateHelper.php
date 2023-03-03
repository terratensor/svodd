<?php

namespace App\helpers;

use DateTimeImmutable;

class DateHelper
{
    public static function showDateFromTimestamp(int $timestamp, string $format = 'H:i d.m.Y'): string
    {
        $date = new DateTimeImmutable();
        $date = $date->setTimeStamp($timestamp);
        return $date->format($format);
    }
}
