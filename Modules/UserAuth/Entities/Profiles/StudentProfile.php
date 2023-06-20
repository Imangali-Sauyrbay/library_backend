<?php

namespace Modules\UserAuth\Entities\Profiles;

use App\Models\Model;
use Modules\UserAuth\Entities\Profiles\Configs\StudentConfig;
use Modules\UserAuth\Entities\User;

/**
 * @mixin IdeHelperStudentProfile
 */
class StudentProfile extends Model
{
    protected $primaryKey = 'user_id';
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $hidden = ['id', 'pivot'];
    protected $fillable = [];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->fillable = StudentConfig::getFillable();
    }

    public static function getMorphName(): string
    {
        return 'StudentProfile';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
