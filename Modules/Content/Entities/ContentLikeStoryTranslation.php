<?php

namespace Modules\Content\Entities;

use Illuminate\Database\Eloquent\Model;

class ContentLikeStoryTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'content__contentlikestory_translations';
}
