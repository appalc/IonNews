<?php

namespace Modules\Content\Entities;

//use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
//    use Translatable;

	protected $table             = 'preferences';
	public $translatedAttributes = [];
	protected $fillable          = ['name', 'value', 'user_id'];
}
