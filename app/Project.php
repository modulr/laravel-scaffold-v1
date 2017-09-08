<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'quotes', 'start_date', 'status', 'description', 'owner_id', 'priority_id', 'contact_id'];

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function priority()
    {
        return $this->belongsTo(Priority::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

}
