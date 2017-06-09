<?php

namespace Modules\Content\Entities;

// use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class ContentImages extends Model
{
    // use Translatable;

    protected $table = 'content__contentimages';
    public $translatedAttributes = [];
    protected $fillable = [
              'content_id',
              'image_path',
    ];
}
