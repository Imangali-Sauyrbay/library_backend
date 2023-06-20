<?php

namespace Modules\UserAuth\Http\Requests;

use App\Http\Requests\ApiFormRequest;
use App\Rules\PositiveRule;
use App\Services\ProvideModelsService;

class RegLinkStoreRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $library = ProvideModelsService::getLibraryClass();
        $libraryTableName = (new $library)->getTable();

        return [
            'useCount' => ['required', 'integer', new PositiveRule],
            'expires' => 'required|date_format:Y-m-d H:i:s|after_or_equal:today',
            'library_slug' => 'required|exists:' . $libraryTableName . ',slug',
            'role_name' => 'required|exists:roles,name'
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
            'required' => 'required',
            'integer' => 'not_integer',
            'date_format' => 'date_format:Y-m-d H:i:s',
            'after_or_equal' => 'after_or_equal:today',
            'exists' => 'not_exists'
        ];
    }
}
