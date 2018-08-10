<?php

namespace App\Models\Stb;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Saucer extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $appends = ['quantity', 'availables'];

    public function owner()
    {
      return $this->belongsTo(\App\User::class);
    }

    public function images()
    {
        return $this->hasMany(SaucerImage::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function getQuantityAttribute()
    {
        return 1;
    }

    public function getAvailablesAttribute()
    {
        $total = $this->limit;

        foreach ($this->reservations as $reservation) {
            $total = $total - $reservation->quantity;
        }
        return $total;
    }
}
