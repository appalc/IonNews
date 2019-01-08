<?php

namespace Modules\Content\Entities;

use Illuminate\Database\Eloquent\Model;

class Skin extends Model
{
	protected $table             = 'skins';
	public $translatedAttributes = [];
	protected $fillable          = [
		'name',
		'color',
		'color_code',
		'highlight_color',
		'hi_color_code',
		'font',
		'font_size',
	];
}
