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

    public function users()
    {
        return $this->belongsToMany(\App\User::class)->withPivot('approved', 'paid');
    }

    public function attendByUserId($user_id){
        // = in where is optional in this case
        return $this->belongsToMany(\App\User::class)->where('user_id', '=', $user_id);
    }

    public function getEnabledAttribute($value) {
        return (bool) $value;
    }
}
