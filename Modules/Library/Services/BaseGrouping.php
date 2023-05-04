<?php

namespace Modules\Library\Services;

class BaseGrouping extends Grouping
{
    protected static function groupMatches(&$matches, $field, $distance, $minLen = 4)
    {
        $result = [];

        $len = count($matches[$field]);

        $prevMatch = false;
        $start = 0;

        for ($i = 0; $i < $len; $i++) {
            $match = &$matches[$field][$i];
            $next = static::getNext($i, $len, $matches, $field);

            if (! static::checkMatches($match, $next, $distance, $prevMatch, $start, $result)) {
                continue;
            }

            if ($match['length'] < $minLen) {
                continue;
            }

            $result[] = $match;
        }

        return $result;
    }
}
