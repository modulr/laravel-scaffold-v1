<?php

namespace App\Models\Commissions;

use Illuminate\Database\Eloquent\Model;

class RangeOfCommission extends Model
{
    protected $fillable = ['title', 'lower_limit', 'upper_limit', 'type'];
}
