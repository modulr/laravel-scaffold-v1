<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'user_groups';

    protected $fillable = [
        'id', 'title', 'description', 'permissions', 'lock'
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
