<?php

namespace Modules\Content\Entities;

// use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
   

    protected $table = 'content__usergroups';
    public $translatedAttributes = [];
    protected $fillable = ['content_id','role_id'];
}
