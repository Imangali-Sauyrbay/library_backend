<?php

namespace App\Contracts\Database;

interface IMorphTableAlias
{
    public static function getMorphName(): string;
}
