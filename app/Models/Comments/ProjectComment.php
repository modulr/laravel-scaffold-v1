<?php

namespace App\Models\Comments;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectComment extends Model
{
    use SoftDeletes;

    protected $fillable = ['project_id', 'user_id', 'comment'];

    protected $dates = ['deleted_at'];

    protected $table = 'projects_comments';

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function project()
    {
        return $this->belongsTo(\App\Models\Projects\Project::class);
    }
}