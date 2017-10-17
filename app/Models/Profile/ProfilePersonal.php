<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfilePersonal extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id', 'birthday', 'place_of_birth', 'gender_id', 'relationship_id', 'rfc', 'curp', 'nss',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function gender()
    {
        return $this->belongsTo(\App\Models\Lists\ListGender::class);
    }

    public function relationship()
    {
        return $this->belongsTo(\App\Models\Lists\ListRelationship::class);
    }
}
