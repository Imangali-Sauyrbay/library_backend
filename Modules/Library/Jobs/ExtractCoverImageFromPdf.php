<?php

namespace Modules\Library\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Modules\Library\Entities\Book;

class ExtractCoverImageFromPdf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $timeout = 120;
    private $tries = 3;

    private int $coverPage;
    private string $path;
    private string $name;
    private Book $book;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Book $book, int $coverPage, string $path, string $name)
    {
        $this->coverPage = $coverPage;
        $this->path = $path;
        $this->name = $name;
        $this->book = $book;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $image = new \Spatie\PdfToImage\Pdf($this->path);
        $coversDirectory = storage_path('app/covers');
        $imagePath = $coversDirectory . '/'  . \Str::uuid() . '.png';

        if (! File::exists($coversDirectory)) {
            File::makeDirectory($coversDirectory, 0755, true);
        }

        $isStored = $image->setPage($this->coverPage)
            ->width(720)
            ->setOutputFormat('png')
            ->saveImage($imagePath);

        if ($isStored) {
            $this->book->cover()->create([
                'name' => $this->name,
                'path' => $imagePath,
                'mime_type' => 'image/png',
                'extension' => 'png',
            ]);
        }
    }
}
