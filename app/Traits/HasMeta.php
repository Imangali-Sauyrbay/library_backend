<?php

namespace App\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Str;

/**
 * @method Builder|static withMeta(array $attrs)
 */
trait HasMeta
{
    protected ?string $metaTable = null;
    protected ?string $metaKey = null;
    abstract public function getTable();
    abstract public function getKeyName();

    public function scopeWithMeta(Builder $query, array $attrs): Builder
    {
        $metaTable = $this->getMetaTable();
        $table = $this->getTable();
        $metaKey = $this->getMetaKey();
        $key = $this->getKeyName();

        $metaAttrs = array_map(
            fn ($attr) => $metaTable . '.' . $attr,
            $attrs
        );

        return $query->addSelect($metaAttrs)
            ->leftJoin(
                $metaTable,
                $metaTable . '.' . $metaKey,
                '=',
                $table . '.' . $key
            );
    }

    public function storeMeta(array $attrs): bool
    {
        $key = $this->getKeyName();
        $id = $this[$key];
        if (! $id) {
            throw new Exception('Save entity|model before seting meta data!');
        }

        $metaTable = $this->getMetaTable();
        $metaKey = $this->getMetaKey();

        return DB::table($metaTable)->updateOrInsert(
            [$metaKey => $id],
            $attrs
        );
    }

    private function getMetaTable()
    {
        return $this->metaTable ?? 'meta_' . $this->getTable();
    }

    private function getMetaKey()
    {
        return $this->metaKey ?? Str::singular($this->getTable()) . '_' . $this->getKeyName();
    }
}
