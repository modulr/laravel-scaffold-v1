<?php

namespace App\Models\Commissions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commission extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $fillable = ['employee_id', 'amount', 'description', 'status'];

    public function employee()
    {
        return $this->belongsTo(\App\Models\Customers\Employee::class);
    }    

}
