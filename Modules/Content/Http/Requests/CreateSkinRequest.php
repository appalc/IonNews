<?php

namespace Modules\Content\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class CreateSkinRequest extends BaseFormRequest
{
	public function rules()
	{
		return [
			'name'            => 'required|unique:companies',
			'color'           => 'required',
			'highlight_color' => 'required',
			'font'            => 'required',
			'font_size'       => 'required',
		];
	}
	
	public function translationRules()
	{
		return [];
	}
	
	public function authorize()
	{
		return true;
	}
	
	public function messages()
	{
		return [];
	}
	
	public function translationMessages()
	{
		return [];
	}
}
