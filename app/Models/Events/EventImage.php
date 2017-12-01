<?php

namespace App\Models\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class EventImage extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $appends = ['url', 'url_thumbnail'];

    public function event()
    {
        return $this->belongsTo(Events::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url('events/'.$this->event_id.'/images/'.$this->basename);
    }

    public function getUrlThumbnailAttribute()
    {
        if (Storage::url('events/'.$this->event_id.'/images/thumbnail_'.$this->basename))
            return Storage::url('events/'.$this->event_id.'/images/thumbnail_'.$this->basename);

        return Storage::url('events/'.$this->event_id.'/images/'.$this->basename);
    }
}
