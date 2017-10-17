<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileEducation extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id', 'title', 'school_name', 'start_year', 'end_year', 'description',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }
}
