<?php

namespace Modules\UserAuth\Entities\Profiles;

use App\Models\Model;
use Illuminate\Foundation\Auth\User;

class CoworkerProfile extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $hidden = ['id', 'pivot'];

    public static function getMorphName(): string
    {   
        return 'CoworkerProfile';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
