<?php

namespace App\Http\Controllers\Students;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Students\Student;
use App\Models\Students\StudentListCertificate;
use App\Models\Students\StudentListGender;
use App\Models\Students\StudentListPaymentMethod;
use App\Models\Students\StudentListPosition;
use App\Models\Students\StudentListStore;
use App\Models\Students\StudentListStudy;

class StudentController extends Controller
{
    public function register (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'last_name' => 'required|string',
            'birthday' => 'required|date',
            'gender_id' => 'required|integer',
            'study_id' => 'required|integer',
            'position_id' => 'required|integer',
            'years_of_seniority' => 'required|integer',
            'certificate_id' => 'required|integer',
            'payment_method_id' => 'required|integer',
            'cellphone' => 'required|string',
            'phone' => 'required|string',
            'store_id' => 'required|integer',
        ]);

        return Student::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'birthday' => $request->birthday,
            'gender_id' => $request->gender_id,
            'study_id' => $request->study_id,
            'position_id' => $request->position_id,
            'years_of_seniority' => $request->years_of_seniority,
            'certificate_id' => $request->certificate_id,
            'payment_method_id' => $request->payment_method_id,
            'cellphone' => $request->cellphone,
            'phone' => $request->phone,
            'store_id' => $request->store_id,
        ]);
    }

    public function certificates()
    {
        return StudentListCertificate::get();
    }

    public function genders()
    {
        return StudentListGender::get();
    }

    public function paymentMethods()
    {
        return StudentListPaymentMethod::get();
    }

    public function positions()
    {
        return StudentListPosition::get();
    }

    public function stores()
    {
        return StudentListStore::get();
    }

    public function studies()
    {
        return StudentListStudy::get();
    }
}
