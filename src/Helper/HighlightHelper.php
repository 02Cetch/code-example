<?php

namespace App\Helper;

class HighlightHelper
{
    /**
     * Highlights keywords via <u> && <b> tags
     */
    public static function process($keyword, $content): ?string
    {
        $keyword = preg_quote($keyword);
        $words = explode(' ', trim($keyword));

        return preg_replace('/' . implode('|', $words) . '/iu', '<u><b>$0</b></u>', $content);
    }
}
