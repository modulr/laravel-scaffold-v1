<?php

namespace App\Http\Controllers\Students;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Students\Student;
use App\Models\Students\StudentListCertificate;
use App\Models\Students\StudentListGender;
use App\Models\Students\StudentListPaymentMethod;
use App\Models\Students\StudentListPosition;
use App\Models\Students\StudentListStudy;
use App\Models\Students\StudentListState;
use App\Models\Students\StudentListCity;
use App\Models\Students\StudentListStore;

class StudentController extends Controller
{
    public function all()
    {
        return Student::with(
                'certificate',
                'gender',
                'paymentMethod',
                'position',
                'store',
                'study')
            ->latest()
            ->get();
    }

    public function register (Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'last_name' => 'required|string',
            'birthday' => 'required|date',
            'gender_id' => 'required|integer',
            'study_id' => 'required|integer',
            'position_id' => 'required|integer',
            'years_in_position' => 'required|numeric|min:1|max:30',
            'certificate_id' => 'required|integer',
            'payment_method_id' => 'required|integer',
            'cellphone' => 'required|string|min:10|max:10',
            'phone' => 'required|string|min:10|max:10',
            'email' => 'required|string|email|confirmed',
            'store_id' => 'required|integer',
            'store_phone' => 'required|string|min:10|max:10',
            'store_email' => 'required|string|email',
        ]);


        $store = StudentListStore::find($request->store_id);
        $store->store_phone = $request->store_phone;
        $store->store_email = $request->store_email;
        $store->save();

        return Student::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'birthday' => $request->birthday,
            'gender_id' => $request->gender_id,
            'study_id' => $request->study_id,
            'position_id' => $request->position_id,
            'years_in_position' => $request->years_in_position,
            'certificate_id' => $request->certificate_id,
            'payment_method_id' => $request->payment_method_id,
            'cellphone' => $request->cellphone,
            'phone' => $request->phone,
            'email' => $request->email,
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

    public function studies()
    {
        return StudentListStudy::get();
    }

    public function states()
    {
        return StudentListState::get();
    }

    public function cities($stateId)
    {
        return StudentListCity::where('state_id', $stateId)->get();
    }

    public function stores($cityId)
    {
        return StudentListStore::where('city_id', $cityId)->get();
    }
}
