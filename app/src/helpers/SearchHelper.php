<?php

namespace App\helpers;

class SearchHelper
{
    public static array $charactersList = ['!', '"', '$', "'", '(', ')', '-', '/', '<', '@', '\\', '^', '|', '~'];

    /**
     * @param string $queryString
     * @return string
     * Escaping characters in query string
     * As some characters are used as operators in the query string, they should be escaped to avoid query errors
     * or unwanted matching conditions. The following characters should be escaped using backslash (\):
     * https://manual.manticoresearch.com/Searching/Full_text_matching/Escaping
     */
    public static function escapingCharacters(string $queryString): string
    {
        return str_replace(self::$charactersList, '\\\\', $queryString);
    }

    public static function checkGravatarUrl(string $queryString): string
    {
        $pattern = '/((http)s?:\/\/)?(?:www\.)?(gravatar\.com\/?avatar\/)?([a-f0-9]{32})*/';
        preg_match($pattern, $queryString, $matches);
//        var_dump($matches);
        if (count($matches) > 1) {
            $nazamenu = $matches[0];
            $normalize_str = str_replace($matches[1], '', $matches[0]);
            return str_replace($nazamenu, $normalize_str, $queryString);
        }
        return $queryString;
//        $matches[0]
//      print_r($matches);
//        return $queryString;
    }
}
