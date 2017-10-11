<?php

use Illuminate\Database\Seeder;

class ListAreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('list_areas')->insert([
            ['title' => 'Norte'],
            ['title' => 'Centro'],
            ['title' => 'Sur']
        ]);
    }
}
