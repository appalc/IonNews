<?php

namespace Modules\Content\Http\Requests;

use Modules\Core\Internationalisation\BaseFormRequest;

class UpdateSkinRequest extends BaseFormRequest
{
	public function rules()
	{
		$skinId = $this->route()->getParameter('skins');

		return [
			'name'            => "required|unique:skins,name,{$skinId}",
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
