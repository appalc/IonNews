<?php

namespace Modules\Content\Entities;

// use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class UserLikedStory extends Model
{
	// use Translatable;
	
	protected $table             = 'user_liked_stories';
	public $translatedAttributes = [];
	protected $fillable          = ['user_id', 'story_id'];
}
