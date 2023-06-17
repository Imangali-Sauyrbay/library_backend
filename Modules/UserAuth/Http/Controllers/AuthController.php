<?php

namespace Modules\UserAuth\Http\Controllers;

use App\Services\CheckUnfilledFields;
use App\Traits\LimitRate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Code;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\UserAuth\Entities\Role;
use Modules\UserAuth\Entities\User;
use Modules\UserAuth\Http\Requests\LoginRequest;
use Modules\UserAuth\Http\Requests\RegisterUserRequest;

class AuthController extends Controller
{
    use LimitRate;

    protected int $maxAttempts = 3;
    protected int $decaySeconds = 120;

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $key = $request->ip() . $data['email'];
        $responce = $this->limitRate($key);
        if ($responce) {
            return $responce;
        }

        if (! Auth::attempt($data, true)) {
            return $this->getLoginFailResponce($key, ['error' => true,
                'message' => 'Credentials not match',
            ]);
        }


        $request->session()->regenerate();
        $this->clearRate($key);

        /** @var User $user */
        $user = auth()->user();
        return response()->json(['user' => $user], Code::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $guards = array_keys(config('auth.guards'));

        foreach ($guards as $guard) {
            $guard = app()['auth']->guard($guard);

            if ($guard instanceof \Illuminate\Auth\SessionGuard) {
                $guard->logout();
            }
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['success' => true]);
    }

    public function register(RegisterUserRequest $register)
    {
        $data = $register->validated();
        $data['password'] = Hash::make($data['password']);

        /** @var User $user */
        $user = User::create($data);
        $role = $register->has('role') ? $register->input('role') : 'user';
        $user->roles()->sync([Role::where('name', $role)->first()->id]);
        Auth::login($user);

        return response()->json(
            [
                'user' => $user,
                'success' => true,
            ],
            Code::HTTP_CREATED
        );
    }

    public function me(): JsonResponse
    {
        $user = User::with(
            ['roles', 'adminProfile', 'studentProfile', 'coworkerProfile']
        )->findOrFail(auth()->user()->id);

        $unfilled = CheckUnfilledFields::getUnfilledFields($user);

        if(!empty($unfilled)) {
            return CheckUnfilledFields::getResponse($unfilled);
        }

        return response()->json($user);
    }
}
