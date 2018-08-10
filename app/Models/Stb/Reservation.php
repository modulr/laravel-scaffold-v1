<?php

namespace App\Models\Stb;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function saucer()
    {
        return $this->belongsTo(Saucer::class);
    }

    public function user()
    {
      return $this->belongsTo(\App\User::class);
    }
}
