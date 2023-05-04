<?php

namespace App\Models\Helpers;

class AddressRules extends BaseAddressRules
{
    public static function getRules($prefix = ''): array
    {
        $result = [];

        foreach (static::$rules as $key => $val) {
            $result[$prefix . $key] = $val;
        }

        $result[$prefix . 'lat'] = ['nullable',
            fn ($attribute, $value, $fail) => AddressRules
            ::isValidNumber($value, -90, 90) ? $fail($attribute) : null,
        ];

        $result[$prefix . 'lng'] = ['nullable',
            fn ($attribute, $value, $fail) => AddressRules
            ::isValidNumber($value, -180, 180) ? $fail($attribute) : null,
        ];

        return $result;
    }
}
