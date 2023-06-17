<?php

namespace Modules\UserAuth\Entities;

use App\Models\Model;

/**
 * @mixin IdeHelperRole
 */
class Role extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $hidden = ['id', 'pivot'];
    protected $fillable = ['name'];

    public static function getMorphName(): string
    {
        return 'Role';
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function registrationLinks()
    {
        return $this->hasMany(RegistrationLink::class);
    }

    public function abilities()
    {
        return $this->morphToMany(Ability::class, 'abilitiable');
    }
}
