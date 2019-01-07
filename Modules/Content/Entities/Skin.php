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
		'highlight_color',
		'font',
		'font_size',
	];
}
