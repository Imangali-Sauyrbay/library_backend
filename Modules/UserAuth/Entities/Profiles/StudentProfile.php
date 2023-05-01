<?php

namespace Modules\UserAuth\Entities;

use App\Models\Model;

class StudentProfile extends Model
{
    public $timestamps = false;
    protected $guarded = ['id'];
    protected $hidden = ['id', 'pivot'];

    public static function getMorphName(): string
    {   
        return 'ReaderProfile';
    }
}
