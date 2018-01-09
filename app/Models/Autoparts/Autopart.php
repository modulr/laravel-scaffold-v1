<?php

namespace App\Models\Autoparts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Autopart extends Model
{
    use SoftDeletes;
    use Userstamps;

    protected $dates = ['deleted_at'];

    protected $guarded = ['id'];

    public function images()
    {
        return $this->hasMany(AutopartImage::class);
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
