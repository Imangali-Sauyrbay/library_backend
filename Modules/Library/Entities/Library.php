<?php

namespace Modules\Library\Entities;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Library\Database\factories\LibraryFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @mixin IdeHelperLibrary
 */
class Library extends Model
{
    use HasFactory, HasSlug;

    protected $guarded = ['id'];
    protected $hidden = ['id', 'pivot'];
    protected $fillable = [
        'title',
        'displayAddress',
        'latitude',
        'longitude',
    ];

    public static function getMorphName(): string
    {
        return 'Library';
    }

    public function books()
    {
        return $this->morphMany(Book::class, 'bookable');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(64);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function newFactory()
    {
        return LibraryFactory::new();
    }
}
