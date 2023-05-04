<?php

namespace Modules\Library\Entities;

use App\Models\Model;
use Laravel\Scout\Searchable;
use Modules\Library\Services\FilterMeilisearchResults;
use Modules\Library\Services\FormatMeilisearchRawService;
use Modules\Library\Services\MeilisearchResultGrouping;

/**
 * @mixin IdeHelperPage
 */
class Page extends Model
{
    use Searchable;

    public $timestamps = false;

    protected $guarded = [];
    protected $hidden = ['pivot'];
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

        MeilisearchResultGrouping::
        groupCloseEnoughResult($items['hits'], 10, 'content');

        FormatMeilisearchRawService::formatRawItems($items);

        $items['hits'] =
        FilterMeilisearchResults::filterEmpties($items['hits'], 'content');

        $books = [];
        static::formatItems($items, $books);
        $items['hits'] = array_values($items['hits']->all());

        return [
            ...$items,
            'books' => $books,
        ];
    }

    private static function formatItems(&$items, &$books)
    {
        $ids = collect($items['hits'])->pluck('id');
        $pages = Page::whereIn('id', $ids)
            ->with(['book', 'book.bookable', 'book.bookable.address'])
            ->select(['id', 'book_slug'])
            ->get()
            ->pluck(null, 'id');

        $items['hits'] = collect($items['hits'])->map(function ($item) use (&$pages) {
            if (! $pages->has($item['id'])) {
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
