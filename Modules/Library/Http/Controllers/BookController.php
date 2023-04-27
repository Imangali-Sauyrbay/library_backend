<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\Library;
use Modules\Library\Entities\Page;
use Modules\Library\Http\Requests\CreateBookRequest;
use Modules\Library\Jobs\ExtractDataFromPdf;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = 20;

        return Page::searchWithMatches(
            (string) ($request->query('search') ?? ''),
            $perPage
        );
    }

    public function cover(Book $book)
    {
        if (! File::exists($book->cover->path)) {
            return response('', 404);
        }

        return response()->file($book->cover->path);
    }

    public function pdf(Book $book)
    {
        if (! \File::exists(storage_path('app/' . $book->eBook->path))) {
            return response('', 404);
        }

        return response()->streamDownload(
            function () use ($book) {
                $fileStream = fopen($book->eBook->path, 'rb');
                fpassthru($fileStream);
                fclose($fileStream);
            },
            $book->eBook->name
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateBookRequest $request)
    {
        $data = $request->validated();

        /** @var Library */
        $lib = Library::where('slug', $data['librarySlug'])->firstOrFail();

        $book = $lib->books()->create([
            'identifier' => $data['identifier'],
            'title' => $data['title'],
            'description' => $data['description'],
            'authors' => $data['authors'],
            'quantity' => $data['quantity'],
        ]);

        if (isset($data['pdf'])) {
            $this->savePdf($request, $book, ! isset($data['cover']), $data['coverPage']);
        }

        if (isset($data['cover'])) {
            $this->saveCover($request, $book);
        }

        return response()->json($book, 201);
    }

    /**
     * Show the specified resource.
     */
    public function show(Book $book)
    {
        return response()->json($book->only(
            [
                'title',
                'authors',
                'description',
                'identifier',
                'quantity',
                'slug',
            ]
        ), 200);
    }

    private function saveCover($request, Book $book)
    {
        $file = $request->file('cover');
        $path = $file->store('covers');
        $book->cover()->create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
        ]);
    }

    private function savePdf($request, Book $book, bool $addCover = false, int $coverPage = 1)
    {
        [$file, $path] = $this->saveEBook($request, $book);
        $path = storage_path('app/' . $path);

        ExtractDataFromPdf::dispatch(
            $book,
            $path,
            $file->getClientOriginalName(),
            $addCover,
            $coverPage
        )->onQueue('high');
    }

    private function saveEBook($request, Book $book)
    {
        $file = $request->file('pdf');
        $path = $file->store('books');

        $book->eBook()->create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'extension' => $file->getClientOriginalExtension(),
        ]);

        return [$file, $path];
    }
}
