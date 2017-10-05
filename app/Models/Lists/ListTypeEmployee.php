<?php

namespace App\Models\Lists;

use Illuminate\Database\Eloquent\Model;
use App\Employee;

class ListTypeEmployee extends Model
{
    protected $fillable = ['title'];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}
