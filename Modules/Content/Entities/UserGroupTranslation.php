<?php

namespace Modules\Content\Entities;

use Illuminate\Database\Eloquent\Model;

class UserGroupTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = [];
    protected $table = 'content__usergroup_translations';
}
