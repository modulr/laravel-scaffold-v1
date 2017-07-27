<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id', 'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
