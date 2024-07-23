<?php

namespace App\helpers;

use App\models\Comment;
use yii\helpers\Html;

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
        $queryString = iconv($charset, "UTF-8", $queryString);

        /**
         * https://regex101.com/r/7S82Sp/1
         * https://regex101.com/r/KNhsNk/1
         * https://regex101.com/r/94BhCb/1
         *
         */
        $patterns = [
            '/(https?:\/\/)(www\.gravatar\.com\/avatar\/[a-z0-9]{32}\.jpg)(\?d=identicon)?/imu',
            '/(https?:\/\/)?[\w-]+\.рф\/avatars\/[a-z0-9]{2}?\/[a-z0-9]{2}?\/[a-z0-9]{32}\.png*/imu',
            '/(https?:\/\/)?xn----8sba0bbi0cdm.xn--p1ai\/avatars\/[a-z0-9]{2}\/[a-z0-9]{2}\/[a-z0-9]{32}\.png*/imu',
        ];

        foreach ($patterns as $key => $pattern) {
            // Регулярное выражение для определения url аватары пользователя в строке,
            // делит на 2 группы протокол http/s и полный url
            preg_match($pattern, $queryString, $matches);

            // Если есть совпадения, определяем протокол, чтобы вырезать его из итоговой строки запроса
            if ($matches) {
                $protocol = $matches[1] ?? '';
                switch ($key) {
                    case 0:
                        // вырезаем из строки протокол
                        $queryString = str_replace("$protocol", '', $queryString);
                        //                        $queryString = self::getAvatarHash($queryString);
                        break;
                    case 1:
                        // вырезаем из строки домен фкт и протокол, если запрос будет без протокола, то только домен фкт
                        $queryString = str_replace("{$protocol}фкт-алтай.рф", '', $queryString);
                        //                        $queryString = self::getAvatarHash($queryString);
                        break;
                    case 2:
                        // вырезаем из строки домен фкт и протокол, если запрос будет без протокола, то только домен фкт
                        $queryString = str_replace("{$protocol}xn----8sba0bbi0cdm.xn--p1ai", '', $queryString);
                        //                        $queryString = self::getAvatarHash($queryString);
                        break;
                }
            }
        }

        // Если ничего не совпало возвращаем строку без изменений.
        // Здесь нет смыла проверять оба шаблона одновременно потому, что так, как сейчас настроен поиск,
        // такое условие не может быть выполнено. Т.е. если в строке 2 url аватар разных пользователей,
        // то ничего не будет найдено, что бы это срабатывала, надо перенастраивать запрос на (and) между url
        return $queryString;
    }

    /**
     * Функция возвращает строку хэша аватары пользователя
     * @param string $avatar_file
     * @return string
     */
    public static function getAvatarHash(string $avatar_file): string
    {
        $result = '';
        $needles = [
            '//www.gravatar.com/avatar/',
            '/avatars/'
        ];

        foreach ($needles as $key => $needle) {
            $start = strpos($avatar_file, $needle);
            if ($start !== false) {
                switch ($key) {
                    case 0:
                        $result = substr($avatar_file, strlen($needle), 32);
                        break;
                    case 1:
                        $result = substr($avatar_file, strlen($needle) + 6, 32);
                        break;
                }
            }
        }

        return $result;
    }

    /**
     * Функция показывает и подсвечивает хэш аватара пользователя
     * @param Comment $comment
     * @return string
     */
    public static function showAvatarHash(Comment $comment): string
    {
        $result = '';
        if (($comment->highlight['avatar_file'][0] ?? '') !== '') {
            $result = self::getAvatarHash($comment->avatar_file);
        }

        return Html::tag('mark', $result);
    }

    public static function transformString($input)
    {
        $mapping = [
            'a' => 'ф', 'b' => 'и', 'c' => 'с', 'd' => 'в', 'e' => 'у',
            'f' => 'а', 'g' => 'п', 'h' => 'р', 'i' => 'ш', 'j' => 'о',
            'k' => 'л', 'l' => 'д', 'm' => 'ь', 'n' => 'т', 'o' => 'щ',
            'p' => 'з', 'q' => 'й', 'r' => 'к', 's' => 'ы', 't' => 'е',
            'u' => 'г', 'v' => 'м', 'w' => 'ц', 'x' => 'ч', 'y' => 'н',
            'z' => 'я', '`' => 'ё', '[' => 'х', ']' => 'ъ', ',' => 'б',
            '.' => 'ю', ';' => 'ж', '\'' => 'э'
        ];

        $output = '';
        for ($i = 0; $i < strlen($input); $i++) {
            $char = strtolower(substr($input, $i, 1));
            if (isset($mapping[$char])) {
                $output .= $mapping[$char];
            } else {
                $output .= $char;
            }
        }

        return $output;
    }

    /**
     * Escapes unclosed double quotes in a string, so that ManticoreSearch can't confuse them with its own
     * @param string $string
     * @return string
     */
    public static function escapeUnclosedQuotes($string)
    {
        $currently_open = '';
        $position = 0;
        $strLength = strlen($string);

        // Loop through each character in the string
        for ($i = 0; $i < $strLength; $i++) {

            // Skip over escaped double quotes, i.e. \" does not count as an unclosed double quote
            if (substr($string, $i, 2) == "\\\"") {
                $i++;
                continue;
            }

            // $string = self::replaceAsterisk($string, $i);

            // If we encounter a double quote, and we are not currently inside a double quote
            // (i.e. we are not currently counting it as an unclosed double quote), then mark the current
            // position as an unclosed double quote
            if (substr($string, $i, 1) === "\"") {
                if ($currently_open === '') {
                    $currently_open = substr($string, $i, 1);
                    $position = $i;
                } else {
                    $currently_open = '';
                }
            }
        }

        // If we have an unclosed double quote, add an escape character before it, so that
        // ManticoreSearch can't confuse it with its own syntax
        if ($currently_open !== "") {
            $string = substr_replace($string, '\\', $position, -$strLength - $position);
        }
        // echo $string;
        return $string;
    }

    /**
     * Replaces asterisk (*) in the string if it is not surrounded by alphanumeric characters.
     * На текущий момент не используется 
     * TODO: дописать так, чтобы астериск не экранировался, в том случае если as an any-term modifier within a phrase search.
     * Т.е. внутри фразы - строки, которая обрамлена ковычками. Например: "управление * системами"
     * @param string $string The input string.
     * @param int $i The position of the asterisk in the string.
     * @return string The modified string.
     */
    public static function replaceAsterisk($string, $i)
    {
        // Get the previous and next characters around the asterisk
        $prevChar = substr($string, $i - 1, 1);
        $nextChar = substr($string, $i + 1, 1);

        // Check if the asterisk is not surrounded by alphanumeric characters
        if (!preg_match('/[a-zA-Zа-яА-Я0-9]/u', $prevChar) && !preg_match('/[a-zA-Zа-яА-Я0-9]/u', $nextChar)) {
            // Replace the asterisk with two backslashes to escape it
            $string = str_replace('*', '\\', $string);
        }

        return $string;
    }
}
