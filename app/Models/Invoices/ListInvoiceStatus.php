<?php

namespace App\Models\Invoices;

use Illuminate\Database\Eloquent\Model;

class ListInvoiceStatus extends Model
{
    protected $fillable = [
        'id', 'name',
    ];

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
