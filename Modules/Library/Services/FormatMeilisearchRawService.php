<?php

namespace Modules\Library\Services;

class FormatMeilisearchRawService
{
    public static function formatRawItems(&$items)
    {
        foreach ($items['hits'] as &$hit) {
            if (isset($hit['_matchesPosition']['content'])) {
                $hit['matches']['content'] = [];
                $result = &$hit['matches']['content'];
                FormatMeilisearchHelpers::getContentText(
                    $hit['_matchesPosition']['content'],
                    $result,
                    $hit
                );
            } else {
                FormatMeilisearchHelpers::simpleParseMatches($hit);
            }

            unset($hit['content']);
            unset($hit['_matchesPosition']);
        }
    }
}
