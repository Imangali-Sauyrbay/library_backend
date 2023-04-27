<?php

namespace Modules\Library\Services;

class Grouping
{
    protected static function getNext($i, $len, &$matches, $field)
    {
        return $i + 1 >= $len ? null : $matches[$field][$i + 1];
    }

    protected static function isCloseEnough(&$match, $next, $distance)
    {
        return $match['start'] + $match['length'] + $distance > $next['start'];
    }

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
        if (static::isCloseEnough($match, $next, $distance)) {
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
