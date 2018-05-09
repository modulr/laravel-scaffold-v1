<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileWork extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function profession()
    {
        return $this->belongsTo(\App\Models\Profile\ProfileListProfession::class);
    }

    public function position()
    {
        return $this->belongsTo(\App\Models\Profile\ProfileListPosition::class);
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\Profile\ProfileListDepartment::class);
    }

    public function boss()
    {
        return $this->belongsTo(\App\User::class, 'boss_id');
    }
}
