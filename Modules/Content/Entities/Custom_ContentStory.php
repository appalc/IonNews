<?php

namespace Modules\Content\Entities;

// use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Custom_ContentStory extends Model
{

    protected $table = 'content__custom_contentstories';
    public $translatedAttributes = [];
    protected $fillable = ['title','sub_title','content','author','custom_url','image','status','tags','all_category','type'];
}
