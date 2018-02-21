<?php

namespace App\Models\Autoparts;

use Illuminate\Database\Eloquent\Model;

class AutopartListMake extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function models()
    {
        return $this->hasMany(AutopartListModel::class, 'make_id');
    }
}
