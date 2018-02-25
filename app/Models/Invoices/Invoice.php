<?php

namespace App\Models\Invoices;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Storage;

class Invoice extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $fillable = ['amount', 'description', 'name', 'basename', 'owner_id', 'invoice_status_id'];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return Storage::url('files/'.$this->owner_id.'/invoices/'.$this->id.'/'.$this->basename);
    }

    public function owner()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function quotes() 
    {
        return $this->belongsToMany(\App\Models\Quotes\Quote::class)->withPivot('amount');
    }

    public function invocie_status()
    {
        return $this->belongsTo(ListInvoiceStatus::class, 'invoice_status_id');
    }
}
