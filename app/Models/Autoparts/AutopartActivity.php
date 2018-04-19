<?php

namespace App\Models\Autoparts;

use Illuminate\Database\Eloquent\Model;

class AutopartActivity extends Model
{
    protected $guarded = ['id'];

    public function autopart()
    {
        return $this->belongsTo(Autopart::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
