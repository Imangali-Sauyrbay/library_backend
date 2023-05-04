<?php

namespace Modules\UserAuth\Entities\Profiles;

use App\Models\Model;
use Modules\UserAuth\Entities\User;

/**
 * @mixin IdeHelperStudentProfile
 */
class StudentProfile extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $hidden = ['id', 'pivot'];

    public static function getMorphName(): string
    {
        return 'StudentProfile';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
