<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lists\ListTypeEmployee;
class Employee extends Model
{
    protected $fillable = ['name', 'role'];

    public function quote()
    {
        return $this->hasOne(Quote::class);
    }

    public function role()
    {
        return $this->belongsTo(ListTypeEmployee::class, 'role');
    }
}
