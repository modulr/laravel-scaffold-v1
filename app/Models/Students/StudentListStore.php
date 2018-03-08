<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentListStore extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

    public function city()
    {
        return $this->belongsTo(StudentListCity::class);
    }

    public function state()
    {
        return $this->belongsTo(StudentListState::class);
    }
    
    public function advisor()
    {
        return $this->belongsTo(\App\User::class);
    }
}
