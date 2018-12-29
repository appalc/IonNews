<?php

namespace Modules\Content\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyGroupRequest extends FormRequest
{
	public function rules()
	{
		$companyGroupId = $this->route()->parameter('companygroups');

		return [
			'name'       => "required|unique:company_groups,name,{$companyGroupId}",
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
