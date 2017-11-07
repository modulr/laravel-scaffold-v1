<?php

namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'start_date', 'status', 'description', 'owner_id', 'priority_id', 'client_id', 'area_id'];

    public function owner()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function priority()
    {
        return $this->belongsTo(\App\Models\Projects\ListPriority::class);
    }

    public function client()
    {
        return $this->belongsTo(\App\Models\Customers\Client::class);
    }

    public function quote()
    {
        return $this->hasMany(\App\Models\Quotes\Quote::class);
    }

    public function area()
    {
        return $this->belongsTo(\App\Models\Lists\ListArea::class);
    }

}
