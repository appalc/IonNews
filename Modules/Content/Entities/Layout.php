<?php

namespace Modules\Content\Entities;

//use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Layout extends Model
{
	//use Translatable;

	protected $table             = 'layouts';
	public $translatedAttributes = [];
	protected $fillable          = ['name', 'options'];
}
