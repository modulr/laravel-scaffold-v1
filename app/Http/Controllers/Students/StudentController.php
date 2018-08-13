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

use App\Jobs\SendStudentRegisteredEmail;
use App\Jobs\SendStudentVerifiedEmail;

use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Storage;
use Auth;
use DB;

class StudentController extends Controller
{
    public function all ()
    {
        if (Auth::user()->hasRole('admin')) {
            return Student::with(
                    'certificates',
                    'gender',
                    'paymentMethod',
                    'position',
                    'store',
                    'study')
                ->latest()
                ->get();
        } else {
            return Student::with(
                    'certificates',
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

    public function show ($id)
    {
        $student = Student::with([
            'certificates',
            'store.advisor'
            ])->find($id);

        return $student;
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
            //'certificate_id' => 'required|integer',
            'certificates' => 'required|array',
            'have_studied' => 'boolean',
            'username' => 'string',
            'year_month' => 'date',
            'cellphone' => 'required|string|min:10|max:10',
            'phone' => 'required|string|min:10|max:10',
            'email' => 'required|string|email|confirmed',
            'store_id' => 'required|integer',
            'store_phone' => 'required|string|min:10|max:10',
            'store_email' => 'required|string|email'
        ]);

        $student = Student::
            where('email', $request->email)
            ->where('name', $request->name)
            ->where('last_name', $request->last_name)
            ->where('certificate_id', $request->certificate_id)
            ->first();

        if ($student)
            return response()->json(['data' => 'duplicated'], 400);


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
            'certificate_id' => 1,
            'have_studied' => $request->have_studied,
            'username' => $request->username,
            'year_month' => $request->year_month,
            'cellphone' => $request->cellphone,
            'phone' => $request->phone,
            'email' => $request->email,
            'store_id' => $request->store_id
        ]);

        $arrayCertificates = collect($request->certificates)->pluck('id');
        $student->certificates()->sync($arrayCertificates);

        SendStudentRegisteredEmail::dispatch($student, $store);

        return response()->json(['data' => 'ok']);
    }

    public function update (Request $request, $id)
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
            //'certificate_id' => 'required|integer',
            'certificates' => 'required|array',
            'have_studied' => 'boolean',
            'username' => 'string|nullable',
            'year_month' => 'date|nullable',
            'cellphone' => 'required|string|min:10|max:10',
            'phone' => 'required|string|min:10|max:10',
            'email' => 'required|string|email',
            'store_id' => 'required|integer',
            'store.store_phone' => 'required|string|min:10|max:10',
            'store.store_email' => 'required|string|email'
        ]);

        return DB::transaction(function () use ($request, $id) {

            $store = StudentListStore::find($request->store_id);
            $store->store_phone = $request->store['store_phone'];
            $store->store_email = $request->store['store_email'];
            $store->save();

            $student = Student::find($id);
            $student->name = $request->name;
            $student->last_name = $request->last_name;
            $student->birthday = $request->birthday;
            $student->gender_id = $request->gender_id;
            $student->study_id = $request->study_id;
            $student->position_id = $request->position_id;
            $student->years_in_position = $request->years_in_position;
            $student->payment_method_id = $request->payment_method_id;
            //$student->certificate_id = $request->certificate_id;
            $student->have_studied = $request->have_studied;
            $student->username = $request->username;
            $student->year_month = $request->year_month;
            $student->cellphone = $request->cellphone;
            $student->phone = $request->phone;
            $student->email = $request->email;
            $student->store_id = $request->store_id;
            $student->save();

            $arrayCertificates = collect($request->certificates)->pluck('id');
            $student->certificates()->sync($arrayCertificates);

            return response()->json(['data' => 'ok']);
        });
    }

    public function verify (Request $request, $id) {

        $this->validate($request, [
            'verified' => 'boolean|required',
            'discount' => 'boolean|required',
            'observations' => 'string|nullable|required',
        ]);

        return DB::transaction(function () use ($request, $id) {
            $student = Student::find($id);
            $student->verified = $request->verified;
            $student->discount = $request->discount;
            $student->observations = $request->observations;
            $student->save();

            SendStudentVerifiedEmail::dispatch($student);

            return response()->json(['data' => 'ok']);
        });
    }

    public function destroy($id)
    {
        return Student::destroy($id);
    }

    public function export ()
    {
        $students = $this->all();

        $x = 0;
        foreach ($students as $key => $value) {
            $x = $x+1;
            $data[] = [
                '#' => $x,
                'Nombre' => $value->name,
                'Apellido' => $value->last_name,
                'Sexo' => $value->gender->name,
                'Fecha de nacimiento' => $value->birthday,
                'Nivel de Estudio Actual' => $value->study->name,
                'Puesto Actual' => $value->position->name,
                'Años de antiguedad en el puesto' => $value->years_in_position,
                'Forma de pago' => $value->paymentMethod->name,
                'Diplomados a los que Aplicas' => $value->certificates->implode('name', ', '),
                'Ha cursado' => ($value->have_studied) ? 'Si' : 'No',
                '¿Cual fue tu nombre de usuario?' => $value->username,
                '¿En que Mes y año cursaste?' => $value->year_month,
                'Mes y año' => $value->year_month,
                'Teléfono Celular' => $value->cellphone,
                'Teléfono Fijo' => $value->phone,
                'Correo electrónico' => $value->email,
                'Validado' => ($value->verified) ? 'Si' : 'No',
                '¿Es beneficio Construrama?' => ($value->discount) ? 'Si' : 'No',
                '¿Valido que el participante es colaborador de tienda Construrama?' => ($value->verified) ? 'Si' : 'No',
                'Justificación de Cambios/avisos/datos de factura' => $value->observations,
                'Fecha registro' => $value->created_at,
                'Numero Tienda' => $value->store->store_id,
                'Construrama' => $value->store->name,
                'Estado' => $value->store->state->name,
                'Ciudad' => $value->store->city->name,
                'CP' => $value->store->cp,
                'Colonia' => $value->store->colony,
                'Calle' => $value->store->street,
                'Numero' => $value->store->number,
                'Teléfono tienda' => $value->store->phone,
                'Teléfono por estudiante' => $value->store->store_phone,
                'Correo electrónico por estudiante' => $value->store->store_email,
                'Holding' => $value->store->holding,
                'Nombre Holding' => $value->store->holding_name,
                'Region' => $value->store->region,
                'Gerencia' => $value->store->management,
                'Asesor' => $value->store->advisor->name,
                'RSO' => $value->store->rso,
                'RFC' => $value->store->rfc,
                'Nombre Comercial' => $value->store->business_name,
            ];
        }

        Excel::create('Estudiantes', function($excel) use ($data) {

            // Set the title
            $excel->setTitle('Estudiantes pre-registrados');

            // Chain the setters
            $excel->setCreator(Auth::user()->name)
                ->setCompany('Universidad Construrama');

            // Call them separately
            $excel->setDescription('Lista de estudiantes pre-registrados en la Universidad Construrama');

            $excel->sheet('hoja 1', function($sheet) use($data) {
                $sheet->fromArray($data);
                $sheet->row(1, function ($row) {
                    $row->setFontSize(12);
                    $row->setFontWeight('bold');
                });
            });

        })->store('xls', storage_path('app/public/students/exports'));

        return Storage::url('students/exports/Estudiantes.xls');
    }

    public function certificates ()
    {
        return StudentListCertificate::get();
    }

    public function genders ()
    {
        return StudentListGender::get();
    }

    public function paymentMethods ()
    {
        return StudentListPaymentMethod::get();
    }

    public function positions ()
    {
        return StudentListPosition::get();
    }

    public function studies ()
    {
        return StudentListStudy::get();
    }

    public function states ()
    {
        return StudentListState::get();
    }

    public function cities ($stateId)
    {
        return StudentListCity::where('state_id', $stateId)->get();
    }

    public function stores ($cityId)
    {
        return StudentListStore::where('city_id', $cityId)->get();
    }
}
