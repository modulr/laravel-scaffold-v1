<?php

namespace App\Models\Tasks;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Task extends Model
{
    use SoftDeletes;
    use Userstamps;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

}
