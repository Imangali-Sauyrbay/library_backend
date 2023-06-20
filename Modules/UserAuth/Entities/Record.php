<?php

namespace Modules\UserAuth\Entities;

use App\Models\Model;

/**
 * @mixin IdeHelperRecord
 */
class Record extends Model
{

    protected $guarded = ['id'];
    protected $hidden = ['id', 'pivot'];
    protected $fillable = ['title', 'desc'];

    public static function getMorphName(): string
    {
        return 'Record';
    }

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
