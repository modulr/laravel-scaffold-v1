<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    public $timestamps = false;
    protected $fillable = ['name', 'desginer_id', 'seller_id', 'project_id', 'customer_id',
                          'request_date','delivery_date','close_date','ammount','status'];

    public function designer()
    {
        return $this->belongsTo(Employee::class, 'desginer_id');
    }

    public function seller()
    {
        return $this->belongsTo(Employee::class, 'seller_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

}
