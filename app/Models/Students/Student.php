<?php

namespace App\Models\Students;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Student extends Model
{
    use SoftDeletes;
    use Userstamps;

    protected $dates = ['deleted_at'];
    protected $guarded = ['id'];

    public function certificates()
    {
        return $this->belongsTo(StudentListCertificate::class);
    }

    public function genders()
    {
        return $this->belongsTo(StudentListGender::class);
    }

    public function paymentMethods()
    {
        return $this->belongsTo(StudentListPaymentMethod::class);
    }

    public function positions()
    {
        return $this->belongsTo(StudentListPosition::class);
    }

    public function stores()
    {
        return $this->belongsTo(StudentListStore::class);
    }

    public function studies()
    {
        return $this->belongsTo(StudentListStudy::class);
    }
}
