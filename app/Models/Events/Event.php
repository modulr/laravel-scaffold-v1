<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Event extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function owner()
    {
      return $this->belongsTo(\App\User::class);
    }

    public function images()
    {
        return $this->hasMany(EventImage::class);
    }

    public function attendings()
    {
        return $this->belongsToMany(\App\User::class)->withPivot('approved', 'paid');
    }
}
