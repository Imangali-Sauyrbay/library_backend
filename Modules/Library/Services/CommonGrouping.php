<?php

namespace Modules\Library\Services;

class CommonGrouping
{
    protected static function getNext($i, $len, &$matches, $field)
    {
        return $i + 1 >= $len ? null : $matches[$field][$i + 1];
    }

    protected static function isCloseEnough(&$match, $next, $distance)
    {
        return $match['start'] + $match['length'] + $distance > $next['start'];
    }
}
