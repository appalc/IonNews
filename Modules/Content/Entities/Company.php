<?php

namespace Modules\Content\Entities;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
	protected $table             = 'companies';
	public $translatedAttributes = [];
	protected $fillable          = ['name', 'user_limit', 'status', 'logo', 'created_by', 'updated_by'];
}
