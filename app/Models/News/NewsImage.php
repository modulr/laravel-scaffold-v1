<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class NewsImage extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['basename', 'extension', 'news_id'];

    protected $appends = ['url'];

    public function news()
    {
        return $this->belongsToMany(News::class);
    }

    public function getUrlAttribute()
    {
        return Storage::url('news/'.$this->news_id.'/'.$this->basename);
    }
}
