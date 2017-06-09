<?php

namespace Modules\Content\Entities;

use Illuminate\Database\Eloquent\Model;

class ContentCompanyTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'content__contentcompany_translations';
}
