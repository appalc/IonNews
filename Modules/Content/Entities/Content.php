<?php

namespace Modules\Content\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    //use Translatable;

    protected $table = 'content__contents';
    public $translatedAttributes = [];
    protected $fillable = ['title','sub_title','content','author','category_id','crawl_url','image','all_users','all_category','expiry_date','status','tags','type'];
}
