<?php

namespace Modules\Content\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyRequest extends FormRequest
{
    public function rules()
    {
        return [
			'name'       => 'required|unique:companies',
			'user_limit' => 'required',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [];
    }
}
