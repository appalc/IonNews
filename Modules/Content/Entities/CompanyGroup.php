<?php

namespace Modules\Content\Entities;

use Illuminate\Database\Eloquent\Model;

class CompanyGroup extends Model
{
	protected $table             = 'company_groups';
	public $translatedAttributes = [];
	protected $fillable          = ['name', 'user_limit', 'company_id'];
}
