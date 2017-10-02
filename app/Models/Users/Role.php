<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id', 'title', 'description', 'permissions', 'lock'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
