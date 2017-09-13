<?php

use Illuminate\Database\Seeder;

class ListDepartmentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('list_departments')->insert([
            ['title' => 'Accounting and Finance'],
            ['title' => 'Human Resource Management'],
            ['title' => 'Marketing'],
            ['title' => 'Purchasing'],
            ['title' => 'Production'],
            ['title' => 'Research and Development'],
        ]);
    }
}
