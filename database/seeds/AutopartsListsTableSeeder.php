<?php

use Illuminate\Database\Seeder;

class AutopartsListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Status
        DB::table('autopart_status')->insert([
            ['name' => 'Available'],
            ['name' => 'Not available'],
            ['name' => 'Separated'],
            ['name' => 'Sold']
        ]);
        // Makes
        DB::table('autopart_list_makes')->insert([
            ['name' => 'Audi', 'order' => 1],
            ['name' => 'BMW', 'order' => 2]
        ]);
        // Models
        DB::table('autopart_list_models')->insert([
            ['name' => 'M1', 'make_id' => 1, 'order' => 1],
            ['name' => 'M2', 'make_id' => 1, 'order' => 2],
            ['name' => 'M3', 'make_id' => 1, 'order' => 3],
            ['name' => 'M4', 'make_id' => 1, 'order' => 4],
            ['name' => 'M5', 'make_id' => 1, 'order' => 5],
            ['name' => 'M6', 'make_id' => 1, 'order' => 6],
            ['name' => 'A1', 'make_id' => 2, 'order' => 1],
            ['name' => 'A3', 'make_id' => 2, 'order' => 2],
            ['name' => 'A4', 'make_id' => 2, 'order' => 3],
            ['name' => 'A5', 'make_id' => 2, 'order' => 4],
            ['name' => 'A6', 'make_id' => 2, 'order' => 5],
            ['name' => 'A7', 'make_id' => 2, 'order' => 6]
        ]);
        // Years
        DB::table('autopart_list_years')->insert([
            ['name' => '2018', 'order' => 9],
            ['name' => '2017', 'order' => 8],
            ['name' => '2016', 'order' => 7],
            ['name' => '2015', 'order' => 6],
            ['name' => '2014', 'order' => 5],
            ['name' => '2013', 'order' => 4],
            ['name' => '2012', 'order' => 3],
            ['name' => '2011', 'order' => 2],
            ['name' => '2010', 'order' => 1]
        ]);
    }
}
