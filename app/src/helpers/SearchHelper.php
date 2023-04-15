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

    public static function processAvatarUrls(string $queryString): string
    {
        $charset = mb_detect_encoding($queryString);
        $queryString =  iconv($charset, "UTF-8", $queryString);

        // https://regex101.com/r/7S82Sp/1
        $pattern = '/(https?:\/\/)(www\.gravatar\.com\/avatar\/[a-z0-9]{32}\.jpg)(\?d=identicon)?/imu';
        preg_match($pattern, $queryString, $matches);
        if ($matches) {
            $protocol = $matches[1] ?? '';
            // вырезаем из строки протокол
            return str_replace("$protocol", '', $queryString);
        }

        // Регулярное выражение для определения url аватары пользователя в строке,
        // делит на 2 группы протокол http/s и полный url
        // https://regex101.com/r/KNhsNk/1
        $pattern = '/(https?:\/\/)?[\w-]+\.рф\/avatars\/\d+\/\w+\/[a-zA-Z0-9]{32}\.png*/imu';
        preg_match($pattern, $queryString, $matches);
        // Если есть совпадения, определяем протокол, чтобы вырезать его из итоговой строки запроса
        if ($matches) {
            $protocol = $matches[1] ?? '';
            // вырезаем из строки домен фкт и протокол, если запрос будет без протокола, то только домен фкт
            return str_replace("{$protocol}фкт-алтай.рф", '', $queryString);
        }

        // Если ничего не совпало возвращаем строку без изменений.
        // Здесь нет смыла проверять оба шаблона одновременно потому, что так, как сейчас настроен поиск,
        // такое условие не может быть выполнено. Т.е. если в строке 2 url аватар разных пользователей,
        // то ничего не будет найдено, что бы это срабатывала, надо перенастраивать запрос на (and) между url
        return $queryString;
    }
}
