<?php

namespace Modules\UserAuth\Entities;

use App\Contracts\Database\IMorphTableAlias;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Modules\UserAuth\Database\factories\UserFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable implements IMorphTableAlias
{
    use HasFactory, HasApiTokens, HasSlug;

    protected $guarded = ['id'];
    protected $hidden = ['id', 'pivot', 'password', 'remember_token'];

    public static function getMorphName(): string
    {
        return 'user';
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['lastname', 'firstname'])
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(64);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}
