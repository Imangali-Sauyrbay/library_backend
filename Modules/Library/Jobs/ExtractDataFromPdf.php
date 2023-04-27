<?php

namespace Modules\Library\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Library\Entities\Book;

class ExtractDataFromPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Book $book;
    private string $path;
    private string $name;
    private bool $addCover;
    private int $coverPage;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Book $book,
        string $path,
        string $name,
        bool $addCover = false,
        int $coverPage = 1
    ) {
        $this->book = $book;
        $this->path = $path;
        $this->name = $name;
        $this->addCover = $addCover;
        $this->coverPage = $coverPage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->addCover) {
            ExtractCoverImageFromPdf::dispatch(
                $this->book,
                $this->coverPage,
                $this->path,
                $this->name
            )
                ->onQueue('high');
        }

        ExtractTextFromPdf::dispatch($this->book, $this->path)
            ->onQueue('high');
    }
}
