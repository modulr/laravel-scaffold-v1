<?php

namespace App\Models\Autoparts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutopartListStatus extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $table = 'autopart_list_status';
    protected $guarded = ['id'];
}
