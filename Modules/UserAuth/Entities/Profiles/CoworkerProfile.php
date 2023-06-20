<?php

namespace Modules\UserAuth\Entities\Profiles;

use App\Models\Model;
use App\Services\ProvideModelsService;
use Illuminate\Foundation\Auth\User;

/**
 * @mixin IdeHelperCoworkerProfile
 */
class CoworkerProfile extends Model
{
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    public static function getMorphName(): string
    {
        return 'CoworkerProfile';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function library()
    {
        return $this->belongsTo(ProvideModelsService::getLibraryClass());
    }
}
