<?php

namespace Modules\Library\Http\Requests;

use App\Http\Requests\ApiFormRequest;

class CreateBookRequest extends ApiFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'identifier' => 'required|string',
            'lang' => 'required|string',
            'country' => 'required|string',
            'released' => 'required|integer|min:1900|max:' . now()->year,
            'title' => 'required|string',
            'description' => 'required|string',
            'authors' => 'required|string',
            'quantity' => 'required|integer',
            'librarySlug' => 'required|string|exists:libraries,slug',
            'cover' => 'required_without:pdf|file|mimes:jpeg,png',
            'pdf' => 'required_without:cover|file|mimes:pdf',
            'coverPage' => 'required_without:cover|integer',
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
}
