<?php

namespace App\Models\Invoices;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $fillable = ['amount', 'description', 'name', 'basename', 'owner_id'];

    public function quotes() 
    {
        return $this->belongsToMany(\App\Models\Quotes\Quote::class);
    }
}
