<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Event extends Model
{
    use SoftDeletes;
    use Userstamps;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function images()
    {
        return $this->hasMany(EventImage::class);
    }
}
