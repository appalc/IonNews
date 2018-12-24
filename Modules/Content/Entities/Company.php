<?php

namespace Modules\Content\Entities;

// use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    // use Translatable;
	protected $table             = 'companies';
	public $translatedAttributes = [];
	protected $fillable          = ['name', 'user_limit'];
}
