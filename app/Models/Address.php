<?php

namespace App\Models;

use App\Models\Helpers\AddressRules;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperAddress
 */
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
        'country_code',
    ];

    public function addressables()
    {
        return $this->morphTo();
    }

    public static function getAddressRules($prefix = ''): array
    {
        return AddressRules::getRules($prefix);
    }
}
