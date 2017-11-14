<?php

namespace App\Models\Lists;

use Illuminate\Database\Eloquent\Model;

class ListTypeEmployee extends Model
{
    protected $fillable = ['title'];

    public function employee()
    {
        return $this->hasOne(\App\Models\Employees\Employee::class);
    }
}
