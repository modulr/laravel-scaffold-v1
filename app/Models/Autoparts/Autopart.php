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

    public function getQrAttribute()
    {
        return Storage::url('autoparts/'.$this->id.'/qr/'.$this->id.'.png');
    }

    public function make()
    {
        return $this->belongsTo(AutopartListMake::class);
    }

    public function model()
    {
        return $this->belongsTo(AutopartListModel::class);
    }

    public function origin()
    {
        return $this->belongsTo(AutopartListOrigin::class);
    }

    public function status()
    {
        return $this->belongsTo(AutopartListStatus::class);
    }

    public function images()
    {
        return $this->hasMany(AutopartImage::class);
    }

    public function years()
    {
        return $this->belongsToMany(AutopartListYear::class, 'autopart_years', 'autopart_id', 'year_id');
    }
}
