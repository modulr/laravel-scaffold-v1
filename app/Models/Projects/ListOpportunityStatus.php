<?php

namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListOpportunityStatus extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'id', 'name',
    ];

    public function opportunity()
    {
        return $this->hasOne(Project::class);
    }
}
