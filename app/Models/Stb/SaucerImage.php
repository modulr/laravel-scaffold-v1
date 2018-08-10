<?php

namespace App\Models\Stb;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class SaucerImage extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $appends = ['url', 'url_thumbnail'];

    public function saucer()
    {
        return $this->belongsTo(Saucer::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url('saucers/'.$this->saucer_id.'/images/'.$this->basename);
    }

    public function getUrlThumbnailAttribute()
    {
        if (Storage::url('saucers/'.$this->saucer_id.'/images/thumbnail_'.$this->basename))
            return Storage::url('saucers/'.$this->saucer_id.'/images/thumbnail_'.$this->basename);

        return Storage::url('saucers/'.$this->saucer_id.'/images/'.$this->basename);
    }
}
