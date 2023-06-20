<?php

namespace Modules\UserAuth\Entities\Profiles;

use App\Models\Model;
use Modules\UserAuth\Entities\User;

/**
 * @mixin IdeHelperAdminProfile
 */
class AdminProfile extends Model
{
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $hidden = ['id', 'pivot'];

    public static function getMorphName(): string
    {
        return 'AdminProfile';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}