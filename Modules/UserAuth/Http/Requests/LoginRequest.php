<?php

namespace Modules\UserAuth\Http\Requests;

use App\Http\Requests\ApiFormRequest;

class LoginRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
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

    public function messages(): array
    {
        return [
            'email.required' => 'email.required',
            'email.email' => 'email.not_valid',
            'password.required' => 'password.required',
            'password.min' => 'password.min:6',
        ];
    }
}
