<?php

use Illuminate\Database\Seeder;

class ListEmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Employees
        DB::table('list_type_employees')->insert([
            ['title' => 'Designer'],
            ['title' => 'Salesman']
        ]);
    }
}
