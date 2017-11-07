<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileFamily extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function gender()
    {
        return $this->belongsTo(\App\Models\Profile\ListGender::class);
    }

    public function relation()
    {
        return $this->belongsTo(\App\Models\Profile\ListRelation::class);
    }
}
