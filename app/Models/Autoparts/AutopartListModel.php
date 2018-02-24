<?php

namespace App\Models\Autoparts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutopartListModel extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function make()
    {
        return $this->belongsTo(AutopartListMake::class);
    }
}
