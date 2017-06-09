<?php

namespace Modules\Content\Entities;

use Illuminate\Database\Eloquent\Model;

class ContentUserTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'content__contentuser_translations';
}
