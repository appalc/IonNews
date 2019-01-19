<?php

namespace Modules\Content\Entities;

//use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class StoryCategory extends Model
{
//    use Translatable;

	protected $table             = 'story_categories';
	public $translatedAttributes = [];
	protected $fillable          = ['story_id', 'category_id'];
}
