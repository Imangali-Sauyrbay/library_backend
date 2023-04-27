<?php

namespace Modules\Library\Services;

class HitFormatter
{
    public static function getHitFormattedText(&$match, &$hit)
    {
        $all = [];
        $start = $match['start'];
        $len = $match['length'];

        $start_bytes = mb_strlen(substr($hit['content'], 0, $start));

        $m = mb_substr($hit['content'], $start_bytes, $len);

        $m = StringFormatService::clearText($m);

        $all['match'] = $m;

        $text = StringFormatService::getCroppedText(
            $hit['content'],
            $start_bytes,
            $len,
            100
        ) ?: '';
        $all['text'] = StringFormatService::clearText($text);
        return $all;
    }
}
