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
use App\Mail\StudentRegistered;
use Illuminate\Support\Facades\Mail;
use Auth;

class StudentController extends Controller
{
    public function all()
    {
        if (Auth::user()->hasRole('admin')) {
            return Student::with(
                    'certificate',
                    'gender',
                    'paymentMethod',
                    'position',
                    'store',
                    'study')
                ->latest()
                ->get();
        } else {
            return Student::with(
                    'certificate',
                    'gender',
                    'paymentMethod',
                    'position',
                    'study',
                    'store')
                ->whereHas('store', function ($query) {
                    $query->where('advisor_id', Auth::user()->id);
                })
                ->latest()
                ->get();
        }
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
            'payment_method_id' => 'required|integer',
            'certificate_id' => 'required|integer',
            'have_studied' => 'boolean',
            'username' => 'string',
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

        $student = Student::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'birthday' => $request->birthday,
            'gender_id' => $request->gender_id,
            'study_id' => $request->study_id,
            'position_id' => $request->position_id,
            'years_in_position' => $request->years_in_position,
            'payment_method_id' => $request->payment_method_id,
            'certificate_id' => $request->certificate_id,
            'have_studied' => $request->have_studied,
            'username' => $request->username,
            'cellphone' => $request->cellphone,
            'phone' => $request->phone,
            'email' => $request->email,
            'store_id' => $request->store_id,
        ]);

        Mail::to($store->advisor->email)
            ->send(new StudentRegistered($student));

        return response()->json(['data' => 'ok']);
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
