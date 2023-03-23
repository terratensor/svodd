<?php

namespace App\helpers;

class SearchHelper
{
    public static function filter(string $queryString): string
    {
        $queryString = trim(filter_var($queryString, FILTER_SANITIZE_ADD_SLASHES), " \!\\-\n\r\t\v\x00");
        return preg_replace('~(.)\1+~i', '\\2', $queryString);
    }
}
