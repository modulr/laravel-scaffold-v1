<?php

use Illuminate\Database\Seeder;

class ListTypeEmployeesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('list_type_employees')->insert([
            ['title' => 'Designer'],
            ['title' => 'Salesman']
        ]);
    }
}
