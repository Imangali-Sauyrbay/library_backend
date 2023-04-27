<?php

namespace Modules\Library\Services;

class MeilisearchResultGrouping extends BaseGrouping
{
    public static function groupCloseEnoughResult(&$hits, $distance, $field)
    {
        foreach ($hits as &$hit) {
            $matches = &$hit['_matchesPosition'];
            if (! isset($matches[$field])) {
                continue;
            }

            $matches[$field] = static::groupMatches($matches, $field, $distance);
        }
    }
}
