<?php

use Illuminate\Database\Seeder;

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

        DB::table('student_list_stores')->insert([
            ['name' => 'Tienda 1'],
            ['name' => 'Tienda 2']
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
    }
}
