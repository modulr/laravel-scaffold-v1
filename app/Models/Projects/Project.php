<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'start_date', 'status', 'description', 'owner_id', 'priority_id', 'client_id', 'area_id'];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function priority()
    {
        return $this->belongsTo(ListPriority::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function quote()
    {
        return $this->hasMany(Quote::class);
    }

    public function area()
    {
        return $this->belongsTo(ListArea::class);
    }

}
