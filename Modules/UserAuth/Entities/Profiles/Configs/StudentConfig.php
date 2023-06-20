<?php

namespace Modules\UserAuth\Entities\Profiles\Configs;

use Illuminate\Support\Carbon;

class StudentConfig implements ProfileConfigContract
{
    public static function getFillable(): array
    {
        return [
            'faculty',
            "department",
            "group",
            "IIN",
            'admission_at',
            'graduation_at'
        ];
    }

    public static function getRules(): array
    {
        return [
            'faculty' => 'string',
            "department" => "string",
            "group" => "string",
            "IIN" => "string|regex:/^\d{12}$/",
            "admission_at" => "integer",
            "graduation_at" => "integer"
        ];
    }

    public static function getMessages(): array
    {
        return [
            'string' => 'string',
            'integer' => 'integer',
            "IIN.regex" => "IIN.regex"
        ];
    }

    public static function castTimes(array $data): array {
        if(array_key_exists('admission_at', $data))
            $data['admission_at'] = Carbon::createFromDate($data['admission_at'], 1, 1);

        if(array_key_exists('graduation_at', $data))
            $data['graduation_at'] = Carbon::createFromDate($data['graduation_at'], 1, 1);

        return $data;
    }
}
