<?php

namespace Modules\UserAuth\Http\Controllers;

use App\Services\ProvideModelsService;
use Illuminate\Http\JsonResponse;
use \Illuminate\Http\Response as Code;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\UserAuth\Entities\RegistrationLink;
use Modules\UserAuth\Entities\Role;
use Modules\UserAuth\Http\Requests\RegLinkIndexRequest;
use Modules\UserAuth\Http\Requests\RegLinkStoreRequest;

class RegistrationLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RegLinkIndexRequest $request)
    {
        $data = $request->validated();
        $perPage = $data['perPage'] ?? 10;

        return response()
        ->json(
            RegistrationLink::paginate($perPage)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegLinkStoreRequest $request) {
        $data = $request->validated();

        /** @var RegistrationLink $link */
        $link = RegistrationLink::make([
            'uuid' => \Str::uuid(),
            'use_count' => $data['useCount'],
            'expires' => $data['expires']
        ]);

        $libraryClass = ProvideModelsService::getLibraryClass();

        $link
            ->library()
            ->associate(
                $libraryClass::where('slug', $data['library_slug'])
                ->first()
            );

        $link
            ->role()
            ->associate(
                Role::where('name', $data['role_name'])
                ->first()
            );

        $link->save();

        return response()->json($link, Response::HTTP_CREATED);
    }

    /**
     * Show the specified resource.
     */
    public function show(string $uuid)
    {
        validator(
            ['uuid' => $uuid],
            ['uuid' => 'required|uuid'],
            ['required' => 'required', 'uuid' => 'uuid']
        )->validate();

        $link = RegistrationLink::with(['role', 'library'])->where('uuid', $uuid)->first();

        return $link
        ? response()->json($link, Response::HTTP_OK)
        : response()->json([], Response::HTTP_NOT_FOUND);
    }
}
