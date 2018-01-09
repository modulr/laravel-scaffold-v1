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
            ['name' => 'M1'],
            ['name' => 'M2'],
            ['name' => 'M3'],
            ['name' => 'M4'],
            ['name' => 'M5'],
            ['name' => 'M6'],
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
