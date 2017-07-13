<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'title', 'done', 'order', 'user_id',
    ];

    public function user()
    {
      return $this->belongsTo(User::class);
    }
}
