<?php

namespace App\Models\Autoparts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutopartListMake extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function models()
    {
        return $this->hasMany(AutopartListModel::class, 'make_id');
    }
}
