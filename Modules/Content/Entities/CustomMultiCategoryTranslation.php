<?php

namespace Modules\Content\Entities;

use Illuminate\Database\Eloquent\Model;

class CustomMultiCategoryTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'content__custommulticategory_translations';
}
