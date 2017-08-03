<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfileWork extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'profession_id', 'position_id', 'department_id', 'boss_id', 'starting_from',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profession()
    {
        return $this->belongsTo(\App\Models\Lists\ListProfession::class);
    }

    public function position()
    {
        return $this->belongsTo(\App\Models\Lists\ListPosition::class);
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\Lists\ListDepartment::class);
    }

    public function boss()
    {
        return $this->belongsTo(\App\User::class, 'boss_id');
    }
}
