<?php

namespace App\Models\Autoparts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutopartListYear extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];
}
