<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;
use Illuminate\Support\Facades\Auth;

class News extends Model
{
    use SoftDeletes;
    use Userstamps;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $casts = [
        'likes' => 'array',
    ];

    protected $appends = ['likes_counter', 'like'];

    public function getLikesCounterAttribute()
    {
        return count($this->likes);
    }

    public function getLikeAttribute()
    {
        if (in_array(Auth::id(), $this->likes))
            return true;

        return false;
    }

    public function images()
    {
        return $this->hasMany(NewsImage::class);
    }
}
