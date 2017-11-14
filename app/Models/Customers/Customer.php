<?php

namespace App\Models\Customers;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['name'];

    public $timestamps = false;

    public function quote()
    {
        return $this->hasOne(\App\Models\Quotes\Quote::class);
    }

    public function client()
    {
        return $this->hasMany(Client::class);
    }
}
