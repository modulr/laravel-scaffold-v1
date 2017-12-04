<?php

namespace App\Models\Files;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;
use Storage;

class File extends Model
{
    use SoftDeletes;
    use Userstamps;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $appends = ['url', 'urlThumbnail'];

    public function owner()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function shares()
    {
        return $this->belongsToMany(\App\User::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url('files/'.$this->owner_id.'/'.$this->basename);
    }

    public function getUrlThumbnailAttribute()
    {
        return Storage::url('files/'.$this->owner_id.'/thumbnail_'.$this->basename);
    }
}
