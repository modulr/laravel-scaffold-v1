<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    public function quote()
    {
        return $this->hasOne(Quote::class);
    }

    public function client()
    {
        return $this->hasMany(Client::class);
    }
}
