<?php

namespace Modules\Content\Entities;

// use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class ContentUser extends Model
{
    // use Translatable;

    protected $table = 'content__contentusers';
    public $translatedAttributes = [];
    protected $fillable = ['user_id','content_id'];
}
