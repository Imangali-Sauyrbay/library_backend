<?php

namespace Modules\UserAuth\Entities;

use App\Models\Model;


class Ability extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $hidden = ['id', 'pivot'];

    public static function getMorphName(): string
    {   
        return 'Abilities';
    }

    public function roles() {
        return $this->morphedByMany(Role::class, 'abilitiable');
    }

    public function users() {
        return $this->morphedByMany(User::class, 'abilitiable');
    }
}
