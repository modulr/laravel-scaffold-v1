<?php

namespace App\Models\Employees;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Employee extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'email', 'phone'];

    public function quote()
    {
        return $this->hasOne(Quote::class);
    }
    
}
