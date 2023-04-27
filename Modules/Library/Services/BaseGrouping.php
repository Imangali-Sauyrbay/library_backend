<?php

namespace Modules\Library\Services;

class BaseGrouping extends Grouping
{
    protected static function groupMatches(&$matches, $field, $distance)
    {
        $result = [];

        $len = count($matches[$field]);

        $isPrevMatched = false;
        $startPos = 0;

        for ($i = 0; $i < $len; $i++) {
            $match = &$matches[$field][$i];
            $next = static::getNext($i, $len, $matches, $field);

            if (! $next || ! static::checkMatches(
                $match,
                $next,
                $distance,
                $isPrevMatched,
                $startPos,
                $result
            )) {
                continue;
            }

            $result[] = $match;
        }

        return $result;
    }
}
