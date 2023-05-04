<?php

namespace Modules\Library\Services;

class Grouping extends CommonGrouping
{
    protected static function chekPrevMatched(&$isPrevMatched, &$match, &$startPos)
    {
        if (! $isPrevMatched) {
            $isPrevMatched = true;
            $startPos = $match['start'];
        }
    }

    protected static function checkMatches(
        &$match,
        $next,
        $distance,
        &$isPrevMatched,
        &$startPos,
        &$result
    ) {
        if ((bool) $next && static::isCloseEnough($match, $next, $distance)) {
            static::chekPrevMatched($isPrevMatched, $match, $startPos);
            return false;
        }

        if ($isPrevMatched) {
            $isPrevMatched = false;
            $result[] = [
                'start' => $startPos,
                'length' => $match['start'] + $match['length'] - $startPos,
            ];

            $startPos = 0;
            return false;
        }
        return true;
    }
}
