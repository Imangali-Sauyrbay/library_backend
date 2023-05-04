<?php

namespace Modules\Library\Services;

class HitFormatter
{
    public static function getHitFormattedText(&$match, &$hit)
    {
        $all = [];
        $start = $match['start'];
        $len = $match['length'];

        $m = substr($hit['content'], $start, $len * 2);
        $m = StringFormatService::clearText($m);
        $m = mb_substr($m, 0, -1);
        $all['match'] = $m;

        $text = StringFormatService::getCroppedText(
            $hit['content'],
            $start,
            $len,
            50
        ) ?: '';
        $all['text'] = StringFormatService::clearText($text);
        return $all;
    }
}
