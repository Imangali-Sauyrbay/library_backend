<?php

namespace Modules\UserAuth\Entities;

use App\Traits\HasMeta;
use Auth;
use Illuminate\Support\Collection;
use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;

/**
 * @mixin IdeHelperPersonalAccessToken
 */
class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasMeta;
    private const TOKEN_TABLE = 'personal_access_tokens';

    protected $guarded = ['id'];

    public static function forCurrentUser(): Collection
    {
        return static::select([
            self::TOKEN_TABLE . '.last_used_at',
            self::TOKEN_TABLE . '.expires_at',
            self::TOKEN_TABLE . '.id',
            self::TOKEN_TABLE . '.created_at',
        ])
            ->withMeta([
                'ip',
                'user_agent',
                'platform_name',
                'browser_family',
                'device_name',
                'device_type',
            ])
            ->where([
                self::TOKEN_TABLE . '.tokenable_id' => Auth::id(),
                self::TOKEN_TABLE . '.tokenable_type' => User::getMorphName(),
            ])
            ->orderByDesc(self::TOKEN_TABLE . '.last_used_at')
            ->get();
    }

    public static function boot()
    {
        parent::boot();

        static::observe(\Modules\UserAuth\Observers\PersonalAccessTokenObserver::class);
    }
}
