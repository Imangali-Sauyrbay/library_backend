<?php

namespace Modules\Library\Entities;

use App\Models\Model;
use Laravel\Scout\Searchable;
use Modules\Library\Services\FormatMeilisearchRawService;
use Modules\Library\Services\MeilisearchResultGrouping;

/**
 * @mixin IdeHelperPage
 */
class Page extends Model
{
    use Searchable;

    public $timestamps = false;

    protected $guarded = ['id'];
    protected $hidden = ['id', 'pivot'];
    protected $fillable = ['page', 'content'];

    public static function getMorphName(): string
    {
        return 'Page';
    }

    public function searchableAs()
    {
        return 'page_index';
    }

    public function toSearchableArray()
    {
        return [
            'id' => (int) $this->id,
            'page' => (int) $this->page,
            'content' => $this->content,
        ];
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_slug', 'slug');
    }

    public static function searchWithMatches($query, $perPage)
    {
        $raw = Page::search(
            $query,
            function ($meiliSearch, string $query, array $options) {
                $options['showMatchesPosition'] = true;
                return $meiliSearch->search($query, $options);
            }
        )->paginateRaw($perPage);

        $items = $raw->items();

        $queryWords = count(explode(' ', $query) ?: ['']);
        $queryWords = max($queryWords, 1);

        MeilisearchResultGrouping::
        groupCloseEnoughResult($items['hits'], 50, 'content', $perPage, $queryWords);

        FormatMeilisearchRawService::formatRawItems($items);
        $books = [];
        static::formatItems($items, $books);

        return [
            ...$items,
            'books' => $books,
        ];
    }

    private static function formatItems(&$items, &$books)
    {
        $ids = collect($items['hits'])->pluck('id');
        $pages = Page::whereIn('id', $ids)
            ->with('book')
            ->select(['id', 'book_slug'])
            ->get()
            ->pluck(null, 'id');

        $items['hits'] = collect($items['hits'])->map(function ($item) use (&$pages) {
            if (! array_key_exists($item['id'], $pages)) {
                return null;
            }

            $item['book_slug'] = $pages[$item['id']]['book_slug'];
            unset($item['id']);
            return $item;
        });

        $books = $pages
            ->mapWithKeys(function ($item) {
                return [$item['book_slug'] => $item['book']];
            });
    }
}
