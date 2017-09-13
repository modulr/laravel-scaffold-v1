<?php

namespace App\Models\Lists;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListPosition extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
    ];
}
