<?php

use Illuminate\Database\Seeder;

class ListGenderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('list_genders')->insert([
            ['title' => 'female'],
            ['title' => 'male']
        ]);
    }
}
