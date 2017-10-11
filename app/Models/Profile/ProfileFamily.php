<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileFamily extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'gender_id', 'relation_id', 'birthday', 'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gender()
    {
        return $this->belongsTo(\App\Models\Lists\ListGender::class);
    }

    public function relation()
    {
        return $this->belongsTo(\App\Models\Lists\ListRelation::class);
    }
}
