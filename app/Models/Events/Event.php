<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\User;

class Event extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function owner()
    {
      return $this->belongsTo(\App\User::class);
    }

    public function images()
    {
        return $this->hasMany(EventImage::class);
    }

    public function attendings()
    {
        return User::whereIn('id', $$this->reservations()->pluck('user_id'));
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reserveFor(User $user)
    {
        $reservation =  $this->reservations()->create([
            'user_id' => $user->id
        ]);

        return $reservation->fresh();
    }
}
