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
use Modules\UserAuth\Entities\Profiles\Configs\StudentConfig;
use Modules\UserAuth\Entities\RegistrationLink;
use Modules\UserAuth\Entities\Role;
use Modules\UserAuth\Entities\User;
use Modules\UserAuth\Http\Requests\LoginRequest;
use Modules\UserAuth\Http\Requests\RegisterUserRequest;
use PhpParser\Node\Stmt\Break_;

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
            $guard = auth()->guard($guard);

            if ($guard instanceof \Illuminate\Auth\SessionGuard) {
                $guard->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return response()->json(['success' => true]);
    }

    public function register(RegisterUserRequest $register, string $link = null)
    {
        $data = $register->validated();
        $data['password'] = Hash::make($data['password']);

        /** @var User $user */
        $user = User::create($data);
        $role = Role::USER;

        $routeKeyName = (new RegistrationLink)->getRouteKeyName();
        $regLink = RegistrationLink::where($routeKeyName, $link)->first();

        if(
            isset($regLink) &&
            in_array($regLink->role->name, Role::ROLES) &&
            $regLink->expires > now() &&
            $regLink['use_count'] > 0
        ) {
            $role = $regLink->role->name;
        }

        $user->roles()->sync([Role::where('name', $role)->first()->id]);

        if($role !== Role::USER) {
            switch($role) {
                case (Role::STUDENT):
                    $user->studentProfile()->create();
                    break;
                case (Role::COWORKER):
                    $profile = $user->coworkerProfile()->make();
                    $profile
                        ->library()
                        ->associate($regLink->library);
                    $profile->save();
                    break;
                case (Role::ADMIN):
                    $user->adminProfile()->create();
                    break;
            }

            $user->registrationLink()->associate($regLink);
        }

        if(isset($regLink)) {
            $regLink['use_count'] = $regLink['use_count'] - 1;
            $regLink->save();
        }

        $user->push();
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

    public function fill(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        $data = validator($request->all(), [
            'firstname' => 'string|max:64',
            'lastname' => 'string|max:64',
            'patronymic' => 'string|max:64'
        ])->validated();

        if(!empty($data)) {
            $user->update($data);
        }

        if($user->isStudent()) {
            $data = validator($request->all(), StudentConfig::getRules(), StudentConfig::getMessages())->validated();

            if(isset($user->studentProfile) && !empty($data)) {
                $data = StudentConfig::castTimes($data);
                $user->studentProfile()->update($data);
            }
        }

        $user->load(['studentProfile', 'coworkerProfile.library', 'adminProfile', 'roles']);
        return response()->json($user);
    }
}
