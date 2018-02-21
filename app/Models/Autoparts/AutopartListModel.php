<?php

namespace App\Models\Autoparts;

use Illuminate\Database\Eloquent\Model;

class AutopartListModel extends Model
{
    protected $guarded = ['id'];
    public $timestamps = false;

    public function make()
    {
        return $this->belongsTo(AutopartListMake::class);
    }
}
