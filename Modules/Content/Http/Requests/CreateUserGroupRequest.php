<?php

namespace Modules\Content\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserGroupRequest extends FormRequest
{
    public function rules()
    {
        return [
			'name'             => 'required|unique:usergroups',
			'user_limit'       => 'required',
			'company_group_id' => 'required',
			'category_id'      => 'required',
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
