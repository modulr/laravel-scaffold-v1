<?php

namespace App\Models\Quotes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Quote extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'description', 'owner_id', 'designer_id', 'salesman_id', 'project_id', 'customer_id', 'request_date', 'delivery_date', 'close_date', 'amount','status_id', 'service_id', 'currency_id'];

    public function designer()
    {
        return $this->belongsTo(\App\Models\Employees\Employee::class, 'designer_id');
    }

    public function salesman()
    {
        return $this->belongsTo(\App\Models\Employees\Employee::class, 'salesman_id');
    }

    public function customer()
    {
        return $this->belongsTo(\App\Models\Customers\Customer::class);
    }

    public function owner()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function project()
    {
        return $this->belongsTo(\App\Models\Projects\Project::class);
    }

    public function attachment()
    {
        return $this->hasMany(\App\Attachment::class);
    }

    public function status()
    {
        return $this->belongsTo(\App\Models\Lists\ListStatusQuote::class);
    }

    public function service()
    {
        return $this->belongsTo(\App\Models\Lists\ListTypeService::class);
    }

    public function currency()
    {
        return $this->belongsTo(\App\Models\Lists\ListTypeCurrency::class);
    }

    public function invoices() 
    {
        return $this->belongsToMany(\App\Models\Invoices\Invoice::class)->withPivot('amount');        
    }

}