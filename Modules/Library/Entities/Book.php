<?php

namespace Modules\Library\Entities;

use App\Models\Fileable;
use App\Models\Imageable;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Scout\Searchable;
use Modules\Library\Database\factories\BookFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @mixin IdeHelperBook
 */
class Book extends Model
{
    use HasFactory, HasSlug, Searchable;

    protected $guarded = ['id'];
    protected $hidden = ['id', 'pivot'];

    protected $fillable = [
        'identifier',
        'lang',
        'country',
        'released',
        'title',
        'description',
        'authors',
        'quantity',
    ];

    public static function getMorphName(): string
    {
        return 'Book';
    }

    public function bookable()
    {
        return $this->morphTo('bookable');
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'book_slug', 'slug');
    }

    public function cover()
    {
        return $this->morphOne(Imageable::class, 'imageable');
    }

    public function eBook()
    {
        return $this->morphOne(Fileable::class, 'fileable');
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

    public function searchableAs()
    {
        return 'book_index';
    }

    public function toSearchableArray()
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'authors' => $this->authors,
            'description' => $this->description,
            'identifier' => $this->identifier,
            'lang' => $this->lang,
            'country' => $this->country,
            'released' => (int) $this->released,
            'updated_at' => $this['updated_at'],
            'created_at' => $this['created_at'],
            'slug' => $this->slug,
            'pages' => $this->pages->map(function ($p) {
                return [
                    'page' => $p->page,
                    'content' => $p->content,
                ];
            })->toArray(),
        ];
    }

    protected static function newFactory()
    {
        return BookFactory::new();
    }
}
