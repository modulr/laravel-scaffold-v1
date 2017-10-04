<?php

namespace App\Models\Lists;

use Illuminate\Database\Eloquent\Model;
use App\Employee;

class ListTypeEmployee extends Model
{
    protected $fillable = ['title'];

    public function role()
    {
        return $this->hasMany(Employee::class, 'id');
    }
}
