<?php

namespace Modules\UserAuth\Http\Requests;

use App\Http\Requests\ApiFormRequest;

class RegisterUserRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:6',
            'email' => 'required|email',
            'password' => 'required|string|min:6',
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
            'password.string' => 'password.string',
            'password.min' => 'password.min:6',
            'name.required' => 'name.required',
            'name.string' => 'name.string',
            'name.min' => 'name.min:6',
        ];
    }
}
