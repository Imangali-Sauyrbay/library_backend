<?php

namespace Modules\UserAuth\Entities;

use App\Models\Model;
use App\Services\ProvideModelsService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @mixin IdeHelperRegistrationLink
 */
class RegistrationLink extends Model
{
    protected $fillable = [
        'use_count',
        'expires',
        'uuid'
    ];

    protected $casts = [
        'expires' => 'datetime'
    ];

    public static function getMorphName(): string
    {
        return 'RegistrationLink';
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function users(): HasMany {
        return $this->hasMany(User::class);
    }

    public function library(): BelongsTo {
        return $this->belongsTo(ProvideModelsService::getLibraryClass());
    }

    public function role(): BelongsTo {
        return $this->belongsTo(Role::class);
    }
}
