<?php

namespace Modules\Library\Services;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class PdfToTextService
{
    public static function getTextFromPdf($path)
    {
        $process = new Process(['/usr/bin/pdftotext', '-layout', '-enc', 'UTF-8', $path, '-']);
        $process->setTimeout(60);
        $process->run();

        if (! $process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }

    public static function getPagesRaw(string &$text)
    {
        $pageNumber = 0;
        return array_values(
            array_filter(
                array_map(
                    function ($page) use (&$pageNumber) {
                        $pageNumber++;
                        if (! $page) {
                            return null;
                        }

                        return [
                            'page' => $pageNumber,
                            'content' => StringFormatService::clearText($page),
                        ];
                    },
                    mb_split('\\f', $text)
                ),
                fn ($item) => (bool) $item
            )
        );
    }
}
