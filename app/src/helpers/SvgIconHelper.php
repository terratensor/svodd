<?php

declare(strict_types=1);

namespace App\helpers;

class SvgIconHelper
{
    /**
     * @mui/icons-material/QuestionAnswer
     * @return string
     */
    public static function questionAnswerIcon(): string
    {
        return '<svg class="menu-icon text-svoddRed-100" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="QuestionAnswerIcon"><path d="M21 6h-2v9H6v2c0 .55.45 1 1 1h11l4 4V7c0-.55-.45-1-1-1m-4 6V3c0-.55-.45-1-1-1H3c-.55 0-1 .45-1 1v14l4-4h10c.55 0 1-.45 1-1"></path></svg>';
    }

    /**
     * @mui/icons-material/Search
     * @return string
     */
    public static function searchIcon(): string
    {
        return '<svg class="menu-icon text-svoddRed-100" focusable="false" aria-hidden="true" viewBox="0 0 24 24" data-testid="SearchIcon"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14"></path></svg>';
    }
}
