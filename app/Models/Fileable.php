<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperFileable
 */
class Fileable extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $hidden = ['id', 'pivot'];
    protected $fillable = [
        'name',
        'path',
        'mime_type',
        'extension',
    ];

    public static function getMorphName(): string
    {
        return 'Fileable';
    }

    public function fileables()
    {
        return $this->morphTo();
    }
}
