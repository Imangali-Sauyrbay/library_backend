<?php

namespace Modules\UserAuth\Http\Controllers;

use Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Modules\UserAuth\Entities\PersonalAccessToken;

/**
 * @mixin Builder
 */
class TokenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Collection
    {
        $currentToken = Auth::user()->currentAccessToken();

        return PersonalAccessToken::forCurrentUser()
            ->map(function ($token) use ($currentToken) {
                $token->is_current = $token->id === $currentToken->id;
                return $token;
            });
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
    }

    /**
     * Show the specified resource.
     */
    public function show()
    {
    }
}
