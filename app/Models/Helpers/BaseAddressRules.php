<?php

namespace App\Models\Helpers;

class BaseAddressRules
{
    protected static $rules = [
        'amenity' => 'nullable|string',
        'displayName' => 'nullable|string',
        'shop' => 'nullable|string',
        'building' => 'nullable|string',
        'house_number' => 'nullable|string',
        'landuse' => 'nullable|string',
        'aeroway' => 'nullable|string',
        'railway' => 'nullable|string',
        'road' => 'nullable|string',
        'municipality' => 'nullable|string',
        'neighbourhood' => 'nullable|string',
        'city_district' => 'nullable|string',
        'city' => 'nullable|string',
        'hamlet' => 'nullable|string',
        'village' => 'nullable|string',
        'town' => 'nullable|string',
        'county' => 'nullable|string',
        'suburb' => 'nullable|string',
        'state' => 'nullable|string',
        'state_district' => 'nullable|string',
        'ISO3166-2-lvl4' => 'nullable|string',
        'postcode' => 'nullable|string',
        'country' => 'nullable|string',
        'country_code' => 'nullable|string|min:2',
    ];

    protected static function isValidNumber($number, $min, $max)
    {
        return is_numeric($number) && $number >= $min && $number <= $max;
    }
}
