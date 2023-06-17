<?php

namespace Modules\UserAuth\Entities;

use App\Models\Model;
use App\Services\ProvideModelsService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class RegistrationLink extends Model
{
    use HasFactory;

    protected $casts = [
        'expires' => 'datetime'
    ];

    public static function getMorphName(): string
    {
        return 'RegistrationLink';
    }

    public function getSlugOptions(): SlugOptions {
        return SlugOptions::create()
            ->generateSlugsFrom(['uuid'])
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(250);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function users() {
        return $this->hasMany(User::class);
    }

    public function library() {
        return $this->belongsTo(ProvideModelsService::getLibraryClass());
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }
}
