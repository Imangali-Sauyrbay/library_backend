<?php

namespace Modules\Library\Services;

class FilterMeilisearchResults
{
    public static function filterEmpties(&$hits, $field)
    {
        return array_filter($hits, function ($hit) use ($field) {
            if (! array_key_exists('matches', $hit)) {
                return false;
            }

            if (count(array_keys($hit['matches'])) <= 1
            && count($hit['matches'][$field]) <= 0) {
                return false;
            }

            return true;
        });
    }
}
