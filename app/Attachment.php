<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class Attachment extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['quote_id', 'basename', 'name', 'user_id'];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return Storage::url('files/'.$this->user_id.'/'.$this->quote_id.'/'.$this->basename);
    }

    public function quote()
    {
      return $this->belongsTo(Quote::class);
    }
}
