<?php

namespace App\Models\Lists;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListArea extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
    ];

    public function project()
    {
        return $this->hasOne(Project::class);
    }
}
