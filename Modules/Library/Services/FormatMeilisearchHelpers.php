<?php

namespace Modules\Library\Services;

class FormatMeilisearchHelpers
{
    // public static function clearPageIdentifier(string $text) {
    //     return preg_replace('/#\[PAGE: \d+\]|PAGE|#\[/', '', $text);
    // }

    // public static function getPageNumber($text, $startPos): int {
    //     $matches = [];
    //     preg_match_all('/#\[PAGE: (\d+)\]/', $text, $matches, PREG_OFFSET_CAPTURE);

    //     foreach ($matches[0] as $match) {
    //         if ($match[1] > $startPos) {
    //             return (int) substr($match[0], 7, -1);
    //         }
    //     }

    //     return count($matches[0]) + 1;
    // }

    // public static function getPageNumber($text, $startPos): int {
    //     $matches = [];
    //     mb_regex_encoding('UTF-8');
    //     mb_ereg_search_init($text);
    //     while (mb_ereg_search_pos('#\[PAGE: (\d+)\]', $flags = 'i')) {
    //         $match = mb_ereg_search_getregs();
    //         if ($match[1] > $startPos) {
    //             return (int) substr($match[0], 7, -1);
    //         }
    //     }

    //     return count($matches) + 1;
    // }

    // public static function identifyPages(string &$text)
    // {
    //     $pageNumber = 0;
    //     $text = mb_ereg_replace_callback('\\f', function () use (&$pageNumber) {
    //         $pageNumber++;
    //         return " \n#[PAGE {$pageNumber}]\n ";
    //     }, $text);

    //     return $pageNumber;
    // }

    public static function getContentText(array $content, array &$result, &$hit)
    {
        $len = count($content);
        for ($i = 0; $i < $len; $i++) {
            $match = $content[$i];
            $result[] = HitFormatter::getHitFormattedText($match, $hit);
        }
    }

    public static function simpleParseMatches(&$hit)
    {
        foreach ($hit['_matchesPosition'] as $key => $value) {
            foreach ($value as $match) {
                $hit['matches'][$key][]['match'] =
                StringFormatService::clearText(
                    mb_substr($hit[$key], $match['start'], $match['length'])
                );
            }
        }
    }
}
