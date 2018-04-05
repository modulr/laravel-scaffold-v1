<?php

namespace App\Models\Modules;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Module extends Model
{
    use SoftDeletes;
    use Userstamps;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

    public function permissions()
    {
        return $this->hasMany(\App\Permission::class);
    }
}
