<?php

namespace Gcambon\Modules\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModuleEntity extends Model
{

    protected $table = "modules";
    public $timestamps = false;

    use SoftDeletes;
}