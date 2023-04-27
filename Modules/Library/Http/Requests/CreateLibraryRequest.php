<?php

namespace Modules\Library\Http\Requests;

use App\Http\Requests\ApiFormRequest;

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
            'displayName' => 'required|string|min:3',
            'coords' => ['required', 'array', 'size:2', function ($attribute, $value, $fail) {
                $lat = $value[0];
                $lng = $value[1];

                if ($this->isValidNumber($lat, -90, 90)) {
                    $fail($attribute);
                }

                if ($this->isValidNumber($lng, -180, 180)) {
                    $fail($attribute);
                }
            },
            ],
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
