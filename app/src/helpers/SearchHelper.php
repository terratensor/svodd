<?php

namespace App\helpers;

use yii\helpers\Html;
use App\models\Comment;
use InvalidArgumentException;

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
        $escapedString = '';
        foreach (str_split($queryString) as $char) {
            foreach (self::$charactersList as $character) {
                if ($char === $character) {
                    $escapedCharacter = '\\' . $character;
                    $queryString = str_replace($character, $escapedCharacter, $queryString);
                    $char = $escapedCharacter;
                }
            }
            $escapedString .= $char;
        }

        // var_dump($queryString);
        return $escapedString;
    }


    public static function containsURL(string $input)
    {
        // Регулярное выражение для поиска URL-адресов в строке
        $pattern = "/(https?:\/\/[^\s]+)/";
        if (preg_match($pattern, $input)) {
            return true;
        } else {
            return false;
        }
    }

    public static function processStringWithURLs(string $input)
    {
        // Регулярное выражение для поиска URL-адресов в строке    
        $pattern = '/(https?:\/\/)?([\w\-]+\.)+[\w\-]+(\/[\w\- .\/?%&=]*)?/';
        
        preg_match_all($pattern, $input, $matches);

        foreach ($matches[0] as $url) {
            // Экранируем специальные символы в URL
            $escapedURL = self::escapingCharacters($url);
            // Заменяем исходный URL на экранированную версию в строке
            $input = str_replace($url, $escapedURL, $input);
        }

        return $input;
    }

    /**
     * @param string $queryString
     * @return string
     *
     * Обработка строки запроса на наличие url аватарок пользователей
     * вырезает из url аватартки его hash и заменяет url на hash
     */
    public static function processAvatarUrls(string $queryString): string
    {
        // $queryString = preg_replace('/[^[:print:]]/', '', $queryString);
        // $charset = mb_detect_encoding($queryString);
        // $queryString = iconv($charset, "UTF-8", $queryString);

        /**
         * https://regex101.com/r/7S82Sp/1
         * https://regex101.com/r/KNhsNk/1
         * https://regex101.com/r/94BhCb/1
         *
         */
        $patterns = [
            '/(https?:\/\/)?(www\.gravatar\.com\/avatar\/[a-z0-9]{32}\.jpg)(\?d=identicon)?/imu',
            '/(https?:\/\/)?[\w-]+\.рф\/avatars\/[a-z0-9]{2}?\/[a-z0-9]{2}?\/[a-z0-9]{32}\.png*/imu',
            '/(https?:\/\/)?xn----8sba0bbi0cdm.xn--p1ai\/avatars\/[a-z0-9]{2}\/[a-z0-9]{2}\/[a-z0-9]{32}\.png*/imu',
        ];

        // Для запросов состоящих из url и ключевых слов
        // Разбить строку по символу пробела
        $parts = explode(' ', $queryString);

        // Обработать каждую часть, на наличие avatar url
        foreach ($parts as &$part) {
            foreach ($patterns as $key => $pattern) {
                // Регулярное выражение для определения url аватары пользователя в строке,
                // делит на 2 группы протокол http/s и полный url
                preg_match($pattern, $part, $matches);
                // Если есть совпадения, определяем протокол, чтобы вырезать его из итоговой строки запроса
                if ($matches) {
                    $protocol = $matches[1] ?? '';
                    switch ($key) {
                        case 0:
                            // вырезаем из строки протокол
                            $part = str_replace("$protocol", '', $part);
                            $part = self::getAvatarHash($part);
                            break;
                        case 1:
                            // вырезаем из строки домен фкт и протокол, если запрос будет без протокола, то только домен фкт
                            $part = str_replace("{$protocol}фкт-алтай.рф", '', $part);
                            $part = self::getAvatarHash($part);
                            break;
                        case 2:
                            // вырезаем из строки домен фкт и протокол, если запрос будет без протокола, то только домен фкт
                            $part = str_replace("{$protocol}xn----8sba0bbi0cdm.xn--p1ai", '', $part);
                            $part = self::getAvatarHash($part);
                            break;
                    }
                }
            }
        }

        // Соединить все части обратно в строку через разделитель символ пробел
        $queryString = implode(' ', $parts);

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
            'www.gravatar.com/avatar/',
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
                        $result = substr($avatar_file, strlen($needle), 32);
                        break;
                    case 2:
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

    public static function containsAvatarHash(string $queryString): bool
    {
        $pattern = "/[a-z0-9]{32}/";

        // Для запросов состоящих из url и ключевых слов
        // Разбить строку по символу пробела
        $parts = explode(' ', $queryString);
        // Обработать каждую часть, на наличие avatar url
        foreach ($parts as &$part) {
            preg_match_all($pattern, $part, $matches);
            foreach ($matches as $key => $value) {
                if (!empty($value)) {
                    return true;
                }
            }
            // foreach ($matches as $key => $value) {
            //     if (empty($value)) {
            //         continue;
            //     }
            //     // var_dump($value, $key);            
            //     $part = str_replace($value[0], '', $part);
            //     $part = "@avatar_file " . $value[0] . " @* ";
            // }
        }

        return false;
    }

    /**
     * Transforms a string from latin to cyrillic using a predefined mapping
     *
     * @param string $input the string to be transformed
     * @return string the transformed string   
     */
    public static function transformString($input)
    {
        $mapping = [
            'a' => 'ф',
            'b' => 'и',
            'c' => 'с',
            'd' => 'в',
            'e' => 'у',
            'f' => 'а',
            'g' => 'п',
            'h' => 'р',
            'i' => 'ш',
            'j' => 'о',
            'k' => 'л',
            'l' => 'д',
            'm' => 'ь',
            'n' => 'т',
            'o' => 'щ',
            'p' => 'з',
            'q' => 'й',
            'r' => 'к',
            's' => 'ы',
            't' => 'е',
            'u' => 'г',
            'v' => 'м',
            'w' => 'ц',
            'x' => 'ч',
            'y' => 'н',
            'z' => 'я',
            '`' => 'ё',
            '[' => 'х',
            ']' => 'ъ',
            ',' => 'б',
            '.' => 'ю',
            ';' => 'ж',
            '\'' => 'э'
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

            // TODO добавить обработку REGEX operator, чтобы можно было использовать астериск вместе с оператором
            // if (substr($string, $i, 1) === "*" && $currently_open === "") {
            //     $string = self::replaceAsterisk($string, $i);
            // } elseif (substr($string, $i, 1) === "*" && $currently_open === "\"") {
            //     $asteriskPosition = $i;
            // }
        }

        // If we have an unclosed double quote, add an escape character before it, so that
        // ManticoreSearch can't confuse it with its own syntax
        if ($currently_open !== "") {
            $string = substr_replace($string, '\\', $position, -$strLength - $position);
            // TODO добавить обработку REGEX operator, чтобы можно было использовать астериск вместе с оператором
            // $string = self::replaceAsterisk($string, $asteriskPosition);
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

    /**
     * Checks if the given query string contains a regex pattern.
     *
     * This function checks if the given query string contains a regex pattern
     * in the form of "REGEX(pattern)". If it does, it returns true, otherwise false.
     *
     * @param string $queryString The query string to check.
     * @return bool True if the query string contains a regex pattern, false otherwise.
     */
    public static function containsRegexPattern(string $queryString): bool
    {
        $regexPattern = '/REGEX\((.+)\)/';
        return preg_match($regexPattern, $queryString) > 0;
    }

    /**
     * Checks if the given query string contains special characters.
     *
     * This function checks if the given query string contains any of the special
     * characters listed in the $charactersList property. If it does, it returns true,
     * otherwise false.
     *
     * @param string $queryString The query string to check.
     * @return bool True if the query string contains special characters, false otherwise.
     */
    public static function containsSpecialChars(string $queryString): bool
    {
        foreach (self::$charactersList as $character) {
            if (strpos($queryString, $character) !== false) {
                return true;
            }
        }
        return false;
    }

    /**
     * Нормализует строку, удаляя диакритические знаки, преобразуя в нижний регистр при необходимости 
     * и обрезая/нормализуя пробелы.
     *
     * @param string $str Строка для нормализации.
     * @param bool $strtolower Преобразовывать ли строку в нижний регистр. По умолчанию true.
     * @return string Нормализованная строка.
     */
    public static function normalizeString(string $str, bool $strtolower = true): string
    {
        if (!is_string($str)) {
            throw new InvalidArgumentException('normalizeString(): Expected string argument.');
        }

        $str = \Normalizer::normalize($str, \Normalizer::NFC);
        $str = preg_replace('/[\r\n\t]/', ' ', $str);
        if ($strtolower) {
            $str = mb_strtolower($str);
        }
        $str = trim(preg_replace('/\s+/', ' ', $str));

        return $str;
    }

    /**
     * Экранирует в строке все скобки, если они не закрыты или закрыты но не открыты
     *          
     * @param string $string The string to escape.
     * @return string Экранированная строка, если исходная строка содержит незакрытые скобки, в противном случае исходная
     * строка.
     */
    public static function escapeUnclosedBrackets(string $string)
    {
        // Проверяем, что строка не пустая
        if (strlen($string) === 0) {
            return $string;
        }

        $open_stack = [];
        $close_stack = [];

        for ($i = 0; $i < strlen($string); $i++) {
            $char = $string[$i];

            if ($char === '(') {
                // Добавляем открывающуюся скобку в стек
                $open_stack[$i] = $char;
            } elseif ($char === ')') {
                // Добавляем закрывающуюся скобку в стек
                $close_stack[$i] = $char;
            }
        }
        // Если любой из стеков не содержит записанных скобок, то экарниурем все скобки
        if (count($open_stack) === 0 || count($close_stack) === 0) {
            return self::escapeAllBrackets($string);
        }
        // Если количество открывающихся скобок не равно количеству закрывающихся скобок
        if (count($open_stack) !== count($close_stack)) {
            // экранировать все скобки
            return self::escapeAllBrackets($string);
        }
        // Предыдущих условий недостаточно, т.к. существуют сцеанрии, 
        // когда первой стоит закрывающая скобка, а открывающая после, например )запрос(
        // Проверяем если минимальное занчение индекса открываюещего стэке больше чем минимальное значение индекса закрывающего,
        // значит ппоследовательность скобок нарушена, и экранируем все скобки
        if (min(array_keys($open_stack)) > min(array_keys($close_stack))) {
            // экранировать все скобки
            return self::escapeAllBrackets($string);
        }

        // Проверяем, сценарий, когда открывающая и закрывающие расположены рядом и не содержат символов между скобок, 
        // например, запрос(), если это так, то экранируем все скобки в строке
        foreach ($open_stack as $key => $value) {
            if (key_exists($key + 1, $close_stack)) {
                // экранировать все скобки
                return self::escapeAllBrackets($string);
            }
        }

        return $string;
    }

    // экранировать все скобки
    private static function escapeAllBrackets(string $string): string
    {
        $string = str_replace('(', '\\(', $string);
        $string = str_replace(')', '\\)', $string);
        return $string;
    }
}
