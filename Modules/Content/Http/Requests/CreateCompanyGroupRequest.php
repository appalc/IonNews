<?php

namespace Modules\Content\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyGroupRequest extends FormRequest
{
    public function rules()
    {
        return [
			'name'       => 'required|unique:companygroups',
			'user_limit' => 'required',
			'company_id' => 'required',
			'skin_id'    => 'required',
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
