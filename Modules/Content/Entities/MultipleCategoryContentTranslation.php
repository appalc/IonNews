<?php

namespace Modules\Content\Entities;

use Illuminate\Database\Eloquent\Model;

class MultipleCategoryContentTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'content__multiplecategorycontent_translations';
}
