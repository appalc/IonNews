<?php

namespace Modules\Content\Entities;

// use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class ContentLikeStory extends Model
{
    // use Translatable;

    protected $table = 'content__contentlikestories';
    public $translatedAttributes = [];
    protected $fillable = ['user_id','content_id'];
}
