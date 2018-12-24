<?php

namespace Modules\Content\Entities;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
	protected $table             = 'user_groups';
	public $translatedAttributes = [];
	protected $fillable          = ['name', 'category_id', 'company_group_id'];
}
