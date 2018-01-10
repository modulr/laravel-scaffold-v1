<?php

namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'registered_date', 'acceptance_date', 'cancelled_date', 'start_date', 'end_date', 'project_status_id', 'opportunity_status_id', 'status', 'description', 'owner_id', 'leader_id', 'supervisor_id', 'priority_id', 'client_id', 'area_id', 'completed_percentage', 'factured_percentage'];

    public function owner()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function leader()
    {
        return $this->belongsTo(\App\Models\Employees\Employee::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(\App\Models\Employees\Employee::class);
    }

    public function priority()
    {
        return $this->belongsTo(\App\Models\Projects\ListPriority::class);
    }

    public function project_status()
    {
        return $this->belongsTo(\App\Models\Projects\ListProjectStatus::class);
    }

    public function opportunity_status()
    {
        return $this->belongsTo(\App\Models\Projects\ListOpportunityStatus::class);
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

    public function comment()
    {
        return $this->hasMany(\App\Models\Comments\ProjectComment::class);
    }
}
