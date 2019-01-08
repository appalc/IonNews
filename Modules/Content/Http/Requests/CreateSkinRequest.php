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
			'color_code'      => 'required',
			'highlight_color' => 'required',
			'hi_color_code'   => 'required',
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
