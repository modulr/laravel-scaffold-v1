<?php

use Illuminate\Database\Seeder;

class ListAreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Areas
        DB::table('list_areas')->insert([
            ['title' => 'Norte'],
            ['title' => 'Centro'],
            ['title' => 'Sur']
        ]);
    }
}
