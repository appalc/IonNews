<?php

namespace Modules\Content\Entities;

// use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class ContentCompany extends Model
{
    // use Translatable;

    protected $table = 'content__contentcompanies';
    public $translatedAttributes = [];
    protected $fillable = ['content_id','company_name'];
}
