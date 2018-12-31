<?php

namespace Modules\Content\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserGroupRequest extends FormRequest
{
    public function rules()
    {
		$userGroupId = $this->route()->getParameter('usergroups');

        return [
			'name'             => "required|unique:user_groups,name,{$userGroupId}",
			'user_limit'       => 'required',
			'category_id'      => 'required',
			'company_group_id' => 'required',
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
