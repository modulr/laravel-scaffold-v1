<?php

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class StudentsListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('student_list_certificates')->insert([
            ['name' => 'Ventas'],
            ['name' => 'Operación de PdV'],
            ['name' => 'Administración'],
            ['name' => 'Servicio al Cliente'],
            ['name' => 'Trabajo en Equipo']
        ]);

        DB::table('student_list_genders')->insert([
            ['name' => 'Mujer'],
            ['name' => 'Hombre']
        ]);

        DB::table('student_list_payment_methods')->insert([
            ['name' => 'Transferencia Bancaria'],
            ['name' => 'CX AL punto']
        ]);

        DB::table('student_list_positions')->insert([
            ['name' => 'Operativo'],
            ['name' => 'Administrativo'],
            ['name' => 'Representante de Ventas o Asesor de Ventas'],
            ['name' => 'Jefe De Área'],
            ['name' => 'Gerente o Administrador'],
            ['name' => 'Director'],
            ['name' => 'Propietario'],
            ['name' => 'Otro']
        ]);

        DB::table('student_list_studies')->insert([
            ['name' => 'Sin estudios'],
            ['name' => 'Primaria'],
            ['name' => 'Secundaria'],
            ['name' => 'Preparatoria'],
            ['name' => 'Técnico'],
            ['name' => 'Universitario'],
            ['name' => 'Maestría']
        ]);

        // student_list_states & student_list_cities
        $result = Excel::load(storage_path('app/public/states_cities.csv'), function($reader) {
            $reader->noHeading();
        }, 'ISO-8859-1')->get();

        $id = null;
        foreach ($result as $key => $row) {
            if ($key != 0) {
                //info($row[0].' '.$row[1].' '.$row[2].' '.$row[4]);
                if ($row[0] != $id) {
                    $id = $row[0];
                    if ($id == 15) {
                        $row[1] = 'Edo. de México';
                    }
                    $stateId = DB::table('student_list_states')->insertGetId([
                        'name' => $row[1],
                        'short_name' => $row[2]
                    ]);
                    DB::table('student_list_cities')->insert([
                        'name' => $row[4],
                        'leader_name' => $row[6],
                        'state_id' => $stateId
                    ]);
                } else {
                    DB::table('student_list_cities')->insert([
                        'name' => $row[4],
                        'leader_name' => $row[6],
                        'state_id' => $stateId
                    ]);
                }
            }
        }

        // student_list_stores
        $result = Excel::load(storage_path('app/public/stores.xlsx'), function($reader) {
            $reader->noHeading();
        })->get();

        foreach ($result as $key => $row) {
            if ($key != 0) {
                $state = DB::table('student_list_states')->where('name', 'like', '%'.$row[14].'%')->first();
                if (!$state) {
                    //info(', notFoundStates , '.$row[7].', '.$row[14].', '.$row[12]);
                    if ($row[14] == 'Estado de México') {
                        $stateId = 15;
                    }
                } else {
                    $stateId = $state->id;
                }

                $city = DB::table('student_list_cities')
                            ->join('student_list_states', 'student_list_states.id', '=', 'student_list_cities.state_id')
                            ->select('student_list_cities.*', 'student_list_states.name as state')
                            ->where('student_list_cities.name', 'like', '%'.$row[12].'%')
                            ->orWhere('student_list_cities.leader_name', 'like', '%'.$row[12].'%')
                            ->where('student_list_cities.state_id', $stateId)
                            ->first();
                if (!$city) {
                    info(', notFoundCities , '.$row[7].', '.$row[14].', '.$row[12]);
                    $cityId = 1;
                } else {
                    $cityId = $city->id;
                }

                DB::table('student_list_stores')->insert([
                    'store_id' => $row[7],
                    'name' => $row[8],
                    'street' => $row[9],
                    'number' => $row[10],
                    'colony' => $row[11],
                    'cp' => $row[13],
                    'city_id' => $cityId,
                    'state_id' => $stateId,
                    'phone' => $row[15],
                    'store_phone' => null,
                    'store_email' => null,

                    'holding_name' => 'por llenar',
                    'holding' => $row[0],
                    'rso' => $row[1],
                    'business_name' => $row[2],
                    'rfc' => $row[3],
                    'region' => $row[4],
                    'management' => $row[5],
                    'seller_name' => $row[6],
                    'seller_email' => 'por llenar'
                ]);
            }
        }
    }
}
