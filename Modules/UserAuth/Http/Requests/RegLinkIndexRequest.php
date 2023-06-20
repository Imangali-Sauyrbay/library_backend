<?php

namespace Modules\UserAuth\Http\Requests;

use App\Http\Requests\ApiFormRequest;

class RegLinkIndexRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'perPage' => 'integer|between:1,50'
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

    public function messages()
    {
        return [
            'integer' => 'validation.integer',
            'between' => 'validation.between:1,50'
        ];
    }
}
