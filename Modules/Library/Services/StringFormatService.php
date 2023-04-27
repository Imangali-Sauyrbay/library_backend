<?php

namespace Modules\Library\Services;

class StringFormatService
{
    public static function clearText(string &$text): string
    {
        $text = mb_convert_encoding($text, 'UTF-8');
        $text = mb_ereg_replace('[^\p{L}\p{N}\p{P}\s<>=$%^&*_\-@]|[ï¿½]|\\[a-z0-9]+?', '', $text);
        $text = mb_ereg_replace('\s+', ' ', $text);
        return mb_ereg_replace('[\x00-\x08\x10\x0B\x0C\x0E-\x19\x7F]', '', $text);
    }

    public static function getCroppedText(
        $text,
        $start,
        $len,
        $cropLength,
        $minCountForTrim = 5
    ) {
        $arr = explode(' ', mb_substr($text, $start - $cropLength, $len + ($cropLength * 2)));

        if (count($arr) > $minCountForTrim) {
            array_pop($arr);
            array_shift($arr);
        }

        return '...'.implode(' ', $arr).'...';
    }
}
