<?php

namespace App\Models\Autoparts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;
use Storage;

class Autopart extends Model
{
    use SoftDeletes;
    use Userstamps;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];
    protected $appends = ['qr'];

    public function images()
    {
        return $this->hasMany(AutopartImage::class);
    }

    public function getQrAttribute()
    {
        return Storage::url('autoparts/'.$this->id.'/qr/'.$this->id.'.png');
    }

    public function make()
    {
        return $this->belongsTo(AutopartListMakes::class);
    }

    public function model()
    {
        return $this->belongsTo(AutopartListModels::class);
    }

    public function year()
    {
        return $this->belongsTo(AutopartListYears::class);
    }

    public function status()
    {
        return $this->belongsTo(AutopartStatus::class);
    }
}
