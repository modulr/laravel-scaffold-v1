<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileContact extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id', 'contact', 'type_id',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function type()
    {
        return $this->belongsTo(\App\Models\Lists\ListContact::class, 'type_id');
    }
}
