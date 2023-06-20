<?php

namespace Modules\UserAuth\Entities\Profiles\Configs;

interface ProfileConfigContract
{
    public static function getFillable(): array;
    public static function getRules(): array;
    public static function getMessages(): array;
}
