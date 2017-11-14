<?php

namespace App\Models\Customers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = ['name', 'email', 'phone_number_1', 'phone_number_2', 'phone_number_3', 'customer_id'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function project()
    {
        return $this->hasOne(\App\Models\Projects\Project::class);
    }
}
