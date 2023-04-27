<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @mixin IdeHelperImageable
 */
class Imageable extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'mime_type',
        'extension',
    ];

    public static function getMorphName(): string
    {
        return 'Imageable';
    }

    public function imageables()
    {
        return $this->morphTo();
    }
}
