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
            ['name' => 'Administrativo'],
            ['name' => 'Director'],
            ['name' => 'Gerente o Administrador'],
            ['name' => 'Jefe de Área'],
            ['name' => 'Operativo'],
            ['name' => 'Propietario'],
            ['name' => 'Representante de Ventas / Asesor de Ventas'],
            ['name' => 'Otro']
        ]);

        DB::table('student_list_studies')->insert([
            ['name' => 'Sin estudios'],
            ['name' => 'Primaria'],
            ['name' => 'Secundaria'],
            ['name' => 'Preparatoria'],
            ['name' => 'Técniico'],
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
    }
}
