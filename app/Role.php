<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'id', 'name',
    ];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
