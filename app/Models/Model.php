<?php

namespace App\Models;

use App\Contracts\Database\IMorphTableAlias;
use Exception;
use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 * @mixin IdeHelperModel
 */
class Model extends BaseModel implements IMorphTableAlias
{
    public static function getMorphName(): string
    {
        throw new Exception('getMorphName not implemented');
    }
}
