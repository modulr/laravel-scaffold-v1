<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentListCertificate extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];
    protected $hidden = ['pivot'];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_certificate', 'student_id', 'certificate_id');
    }
}
