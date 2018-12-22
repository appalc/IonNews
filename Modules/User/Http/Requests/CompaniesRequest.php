<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompaniesRequest extends FormRequest
{
	public function rules()
	{
		return [
			'name'       => 'required',
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
