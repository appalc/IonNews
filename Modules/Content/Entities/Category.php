<?php

namespace Modules\Content\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //use Translatable;

    protected $table = 'content__categories';
    public $translatedAttributes = [];
    protected $fillable = ['name','slug_name','status','priority'];
}
