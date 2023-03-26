<?php

namespace Modules\UserAuth\Http\Controllers;

use Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Code;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Modules\UserAuth\Entities\User;
use Modules\UserAuth\Http\Requests\LoginRequest;
use Modules\UserAuth\Http\Requests\RegisterUserRequest;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();
        $max_attempts = 3;
        $key = $request->ip() . $data['email'];

        $responce = $this->limitRate($key, $max_attempts, 120);
        if ($responce) {
            return $responce;
        }

        if (! Auth::attempt($data)) {
            return $this->getLoginFailResponce($key, $max_attempts);
        }

        $this->clearRate($key);
        $request->session()->regenerate();

        /** @var User $user */
        $user = auth()->user();
        $token = $user->createToken('api', ['*'], now()->addDays(5))->plainTextToken;
        return response()->json(['token' => $token], Code::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

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

        return response()->json(
            [
                'user' => $user,
                'success' => true,
                'token' => $user->createToken('api')->plainTextToken,
            ],
            Code::HTTP_CREATED
        );
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['user' => $request->user()->only(['email', 'name', 'id'])]);
    }

    private function limitRate($key, $maxAttempts, $decaySeconds)
    {
        if (RateLimiter::tooManyAttempts($key, $maxAttempts, $decaySeconds)) {
            return response()->json([
                'error' => true,
                'mesage' => 'Too Many Attempts.',
                'available_at' => $this->getRemainingTime($key),
            ], Code::HTTP_TOO_MANY_REQUESTS);
        }

        RateLimiter::hit($key, $decaySeconds);

        return false;
    }

    private function clearRate($key)
    {
        RateLimiter::clear($key);
    }

    private function getRemainingAttempts($key, $max)
    {
        return RateLimiter::remaining($key, $max);
    }

    private function getRemainingTime($key)
    {
        return now()->addSeconds(RateLimiter::availableIn($key));
    }

    private function getLoginFailResponce($key, $max_attempts)
    {
        $attempts_left = $this->getRemainingAttempts($key, $max_attempts);
        return response()
            ->json([
                'error' => true,
                'message' => 'Credentials not match',
                'max_attempts' => $max_attempts,
                'remaining_attempts' => $attempts_left,
                'remaining_time' => $attempts_left ? null : $this->getRemainingTime($key),
            ], Code::HTTP_UNAUTHORIZED);
    }
}
