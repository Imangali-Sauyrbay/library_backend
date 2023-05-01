<?php

namespace Modules\UserAuth\Entities\Profiles\Configs;

interface ProfileConfigContract {
    public function getFillable(): array;
    public function getRules(): array;
}