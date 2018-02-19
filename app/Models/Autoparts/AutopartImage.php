<?php

namespace App\Models\Autoparts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class AutopartImage extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $appends = ['url', 'url_thumbnail'];

    public function autopart()
    {
        return $this->belongsTo(Autopart::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url('autoparts/'.$this->autopart_id.'/images/'.$this->basename);
    }

    public function getUrlThumbnailAttribute()
    {
        if (Storage::url('autoparts/'.$this->autopart_id.'/images/thumbnail_'.$this->basename))
            return Storage::url('autoparts/'.$this->autopart_id.'/images/thumbnail_'.$this->basename);

        return Storage::url('autoparts/'.$this->autopart_id.'/images/'.$this->basename);
    }
}
