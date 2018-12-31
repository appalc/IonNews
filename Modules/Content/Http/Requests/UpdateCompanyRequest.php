<?php

namespace Modules\Content\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
	public function rules()
	{
		$companyId = $this->route()->getParameter('companies');

		return [
			'name'       => "required|unique:companies,name,{$companyId}",
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
