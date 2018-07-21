<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    protected $guarded = [];

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $casts = [
        'paid' => 'boolean',
        'approved' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approve()
    {
        $this->update([
            'approved' => true,
        ]);

        return $this;
    }
}
