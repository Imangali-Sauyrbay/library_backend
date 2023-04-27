<?php

namespace Modules\Library\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Book;
use Modules\Library\Services\PdfToTextService;

class ExtractTextFromPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $timeout = 120;
    protected $tries = 3;

    private Book $book;
    private string $pathToPDF;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Book $book, string $pathToPDF)
    {
        $this->book = $book;
        $this->pathToPDF = $pathToPDF;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        ini_set('memory_limit', '512M');
        $this->getPdfContent();
    }

    public function getPdfContent()
    {
        $text = PdfToTextService::getTextFromPdf($this->pathToPDF);

        $this->book->pages()->createMany(PdfToTextService::getPagesRaw($text));
    }
}
