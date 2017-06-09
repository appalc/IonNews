<?php

namespace Modules\Content\Entities;

// use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class CustomMultiCategory extends Model
{
    // use Translatable;

    protected $table = 'content__custommulticategories';
    public $translatedAttributes = [];
    protected $fillable = ['custom_content_id','category_id'];
}
