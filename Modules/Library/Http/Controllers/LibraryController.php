<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Library\Entities\Library;
use Modules\Library\Http\Requests\CreateLibraryRequest;

class LibraryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->has('query') && ! empty($request->query('query'))) {
            $query = '%' . $request->query('query', '') . '%';
            return Library::where('title', 'like', $query)
                ->orWhere('displayAddress', 'like', $query)
                ->paginate(10);
        }

        return Library::paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLibraryRequest $request)
    {
        $data = $request->validated();

        Library::create([
            'title' => $data['title'],
            'displayAddress' => $data['displayName'],
            'latitude' => $data['coords'][0],
            'longitude' => $data['coords'][1],
        ]);

        return response('', Response::HTTP_CREATED);
    }

    /**
     * Show the specified resource.
     */
    public function show()
    {
    }
}
