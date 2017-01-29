<?php

namespace Modules\Questions\Entities;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    //use Translatable;

    protected $table = 'questions__comments';
    //public $translatedAttributes = [];
    protected $fillable = ['question_id', 'user_id', 'comment'];
}