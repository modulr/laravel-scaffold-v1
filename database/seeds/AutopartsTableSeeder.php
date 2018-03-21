<?php

use Illuminate\Database\Seeder;

class AutopartsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('autopart_list_makes')->insert([
            ['name' => 'Aston Martin'],
            ['name' => 'Audi'],
            ['name' => 'Bentley'],
            ['name' => 'BMW'],
            ['name' => 'Bugatti'],
            ['name' => 'Ferrari'],
            ['name' => 'Lamborghini'],
            ['name' => 'Maserati'],
            ['name' => 'Mercedes Benz'],
            ['name' => 'Porsche'],
            ['name' => 'Tesla']
        ]);

        DB::table('autopart_list_models')->insert([
            ['name' => 'A1', 'make_id' => 2],
            ['name' => 'A3', 'make_id' => 2],
            ['name' => 'A4', 'make_id' => 2],
            ['name' => 'A5', 'make_id' => 2],
            ['name' => 'A6', 'make_id' => 2],
            ['name' => 'A7', 'make_id' => 2],
            ['name' => 'M1', 'make_id' => 4],
            ['name' => 'M2', 'make_id' => 4],
            ['name' => 'M3', 'make_id' => 4],
            ['name' => 'M4', 'make_id' => 4],
            ['name' => 'M5', 'make_id' => 4],
            ['name' => 'M6', 'make_id' => 4]
        ]);

        DB::table('autopart_list_origins')->insert([
            ['name' => 'New'],
            ['name' => 'Used'],
            ['name' => 'Generic']
        ]);

        DB::table('autopart_list_status')->insert([
            ['name' => 'Available'],
            ['name' => 'Not available'],
            ['name' => 'Separated'],
            ['name' => 'Sold']
        ]);

        DB::table('autopart_list_years')->insert([
            ['name' => '2018'],
            ['name' => '2017'],
            ['name' => '2016'],
            ['name' => '2015'],
            ['name' => '2014'],
            ['name' => '2013'],
            ['name' => '2012'],
            ['name' => '2011'],
            ['name' => '2010']
        ]);
    }
}
