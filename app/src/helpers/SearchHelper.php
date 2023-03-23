<?php

namespace App\helpers;

class SearchHelper
{
    public static function filter(string $queryString, $disabled = false): string
    {
        if (!$disabled) {
            $queryString = trim(filter_var($queryString, FILTER_SANITIZE_ADD_SLASHES), " \!\\-\n\r\t\v\x00");
            $queryString = preg_replace('~(.)\1+~i', '\\2', $queryString);
            $queryString = preg_replace('~(!-)~', '\\2', $queryString);
        }
        return $queryString;
    }
}
