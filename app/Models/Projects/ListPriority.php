<?php

namespace App\Models\Projects;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListPriority extends Model
{
    use SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'id', 'name',
    ];

    public function project()
    {
        return $this->hasOne(Project::class);
    }
}
