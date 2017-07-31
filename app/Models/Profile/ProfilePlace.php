<?php

namespace App\Models\Profile;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfilePlace extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'palce', 'currently', 'description',
    ];

    public function user()
    {
      return $this->belongsTo(User::class);
    }
}
