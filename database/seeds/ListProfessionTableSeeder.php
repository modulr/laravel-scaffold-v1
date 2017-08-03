<?php

use Illuminate\Database\Seeder;

class ListProfessionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('list_professions')->insert([
            ['title' => 'Developer'],
            ['title' => 'Designer'],
            ['title' => 'Software Architect']
        ]);
    }
}
