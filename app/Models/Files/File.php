<?php

namespace App\Models\Files;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class File extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    protected $appends = ['url', 'urlThumbnail'];

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function shares()
    {
        return $this->belongsToMany(\App\User::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url('files/'.$this->user_id.'/'.$this->basename);
    }

    public function getUrlThumbnailAttribute()
    {
        return Storage::url('files/'.$this->user_id.'/thumbnail_'.$this->basename);
    }
}
