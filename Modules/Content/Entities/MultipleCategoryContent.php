<?php

namespace Modules\Content\Entities;

// use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class MultipleCategoryContent extends Model
{
    // use Translatable;

    protected $table = 'content__multiplecategorycontents';
    public $translatedAttributes = [];
    protected $fillable = ['category_id','content_id'];
}
