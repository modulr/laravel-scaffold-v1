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
            ['name' => 'Activa'],
            ['name' => 'Desactiva'],
            ['name' => 'Separada'],
            ['name' => 'Pendiente'],
            ['name' => 'Vendida'],
        ]);
        // Makes
        DB::table('autopart_list_makes')->insert([
            ['name' => 'BMW'],
            ['name' => 'Audi'],
            ['name' => 'Mercedes'],
        ]);
        // Models
        DB::table('autopart_list_models')->insert([
            ['name' => 'M1', 'make_id' => 1],
            ['name' => 'M2', 'make_id' => 1],
            ['name' => 'M3', 'make_id' => 1],
            ['name' => 'M4', 'make_id' => 1],
            ['name' => 'M5', 'make_id' => 1],
            ['name' => 'M6', 'make_id' => 1],
            ['name' => 'A1', 'make_id' => 2],
            ['name' => 'A3', 'make_id' => 2],
            ['name' => 'A4', 'make_id' => 2],
            ['name' => 'A5', 'make_id' => 2],
            ['name' => 'A6', 'make_id' => 2],
            ['name' => 'A7', 'make_id' => 2],
        ]);
        // Years
        DB::table('autopart_list_years')->insert([
            ['name' => '2018'],
            ['name' => '2017'],
            ['name' => '2016'],
            ['name' => '2015'],
            ['name' => '2014'],
            ['name' => '2013'],
            ['name' => '2012'],
            ['name' => '2011'],
            ['name' => '2010'],
        ]);
    }
}
