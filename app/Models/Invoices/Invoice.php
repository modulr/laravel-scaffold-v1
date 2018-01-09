<?php

namespace App\Models\Invoices;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class Invoice extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $fillable = ['amount', 'description', 'name', 'basename', 'owner_id'];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return Storage::url('files/'.$this->owner_id.'/invoices/'.$this->basename);
    }

    public function quotes() 
    {
        return $this->belongsToMany(\App\Models\Quotes\Quote::class);
    }
}
