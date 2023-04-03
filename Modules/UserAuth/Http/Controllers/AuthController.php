<?php

namespace Modules\UserAuth\Http\Controllers;

use App\Traits\LimitRate;
use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Code;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\UserAuth\Entities\User;
use Modules\UserAuth\Http\Requests\LoginRequest;
use Modules\UserAuth\Http\Requests\RegisterUserRequest;

class AuthController extends Controller
{
    use LimitRate;

    protected int $maxAttempts = 30;
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
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['success' => true]);
    }

    public function registerUser(RegisterUserRequest $register)
    {
        $data = $register->validated();
        $data['password'] = Hash::make($data['password']);

        /** @var User $user */
        $user = User::create($data);

        Auth::login($user);

        return response()->json(
            [
                'user' => $user,
                'success' => true,
            ],
            Code::HTTP_CREATED
        );
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['user' => $request->user()]);
    }
}
