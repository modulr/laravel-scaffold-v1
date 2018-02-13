<?php

namespace App\Models\Autoparts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class AutopartListMakes extends Model
{
    use SoftDeletes;
    use Userstamps;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

    public function models()
    {
        return $this->hasMany(AutopartListModels::class, 'make_id');
    }
}
