<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'amenity',
        'displayName',
        'lat',
        'lng',
        'shop',
        'building',
        'house_number',
        'landuse',
        'aeroway',
        'railway',
        'road',
        'municipality',
        'neighbourhood',
        'city_district',
        'city',
        'hamlet',
        'village',
        'town',
        'county',
        'suburb',
        'state',
        'state_district',
        'ISO3166-2-lvl4',
        'postcode',
        'country',
        'country_code'
    ];

    public function addressables() {
        return $this->morphTo();
    }

    public static function getAddressRules($prefix = ''): array {
        return [
            $prefix . 'amenity' => 'nullable|string',
            $prefix . 'displayName' => 'nullable|string',
            $prefix . 'lat' => ['nullable', 'number', function ($attribute, $value, $fail) {
                $lat = $value;

                if ($this->isValidNumber($lat, -90, 90)) {
                    $fail($attribute);
                }
            }],

            $prefix . 'lng' => ['nullable', 'number', function ($attribute, $value, $fail) {
                $lng = $value;

                if ($this->isValidNumber($lng, -180, 180)) {
                    $fail($attribute);
                }
            }],

            $prefix . 'shop' => 'nullable|string',
            $prefix . 'building' => 'nullable|string',
            $prefix . 'house_number' => 'nullable|string',
            $prefix . 'landuse' => 'nullable|string',
            $prefix . 'aeroway' => 'nullable|string',
            $prefix . 'railway' => 'nullable|string',
            $prefix . 'road' => 'nullable|string',
            $prefix . 'municipality' => 'nullable|string',
            $prefix . 'neighbourhood' => 'nullable|string',
            $prefix . 'city_district' => 'nullable|string',
            $prefix . 'city' => 'nullable|string',
            $prefix . 'hamlet' => 'nullable|string',
            $prefix . 'village' => 'nullable|string',
            $prefix . 'town' => 'nullable|string',
            $prefix . 'county' => 'nullable|string',
            $prefix . 'suburb' => 'nullable|string',
            $prefix . 'state' => 'nullable|string',
            $prefix . 'state_district' => 'nullable|string',
            $prefix . 'ISO3166-2-lvl4' => 'nullable|string',
            $prefix . 'postcode' => 'nullable|string',
            $prefix . 'country' => 'nullable|string',
            $prefix . 'country_code' => 'nullable|string|min:2',
        ];
    }
}
