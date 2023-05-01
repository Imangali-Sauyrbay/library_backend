<?php

namespace Modules\Library\Http\Requests;

use App\Http\Requests\ApiFormRequest;
use App\Models\Address;

class CreateLibraryRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|min:3',
            ...Address::getAddressRules('address.')
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    private function isValidNumber($number, $min, $max)
    {
        return ! is_numeric($number) || $number < $min || $number > $max;
    }
}
