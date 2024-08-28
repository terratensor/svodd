<?php

declare(strict_types=1);

namespace frontend\widgets\question;

class MetaQuestion extends \yii\base\Widget
{
    public $question;

    private $maxWords = 20;
    private $maxChars = 150;

    public function run(): string
    {
        $text = $this->question->text;

        // Remove <span class="link"> elements from the text, as they are used
        // to highlight keywords and are not relevant for the meta description.
        $pattern = '/<span class=["\']link["\']>.+?<\/span>/';
        $output = preg_replace($pattern, '', $text);

        // Replace multiple spaces with a single space.
        $output = preg_replace('/\s+/', ' ', $output);

        return $this->truncateText($output);
    }

    /**
     * Truncates the given string to fit the maximum number of words or characters.
     * If the string is longer than the maximum number of words, it truncates the string
     * to the last word that fits within the maximum number of characters.
     * If the string is longer than the maximum number of characters, it truncates the
     * string to the last character that fits within the maximum number of characters
     * and appends an ellipsis.
     *
     * @param string $text The text to truncate.
     *
     * @return string The truncated text.
     */
    public function truncateText(string $text): string
    {
        $count = mb_strlen($text);
        $words = explode(" ", $text);

        if (count($words) <= $this->maxWords) {
            return $text;
        }

        $truncatedText = "";
        foreach ($words as $word) {
            if (mb_strlen($truncatedText) + mb_strlen($word) + 1 <= $this->maxChars) {
                $truncatedText .= $word . " ";
            } else {
                break;
            }
        }

        if (mb_strlen(trim($truncatedText)) < $count) {
            return $this->modifyString(trim($truncatedText));
        }

        return trim($truncatedText);
    }

    /**
     * Modify the string by adding an ellipsis at the end, taking into account punctuation marks.
     *
     * If the last character is a punctuation mark, it is replaced by an ellipsis.
     * Otherwise, the ellipsis is appended at the end.
     *
     * @param string $input
     * @return string
     */
    private function modifyString(string $input)
    {
        $lastChar = mb_substr($input, -1, 1);
        $punctuationMarks = [' ', '.', ',', ':', ';', '…', '-', '–', '—', '=', '+'];

        if (in_array($lastChar, $punctuationMarks)) {
            return substr_replace($input, "…", -1);
        } else {
            return $input . "…";
        }
    }
}
