<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class File extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'basename', 'extension', 'description', 'favorite', 'parent_id', 'type', 'user_id'];

    protected $appends = ['url', 'is_image'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shares()
    {
        return $this->belongsToMany(User::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url('files/'.$this->user_id.'/'.$this->basename);
    }

    public function getIsImageAttribute()
    {
        if (in_array($this->extension, ['jpg', 'jpeg', 'png'])) {
            return true;
        }
        return false;
    }

}
