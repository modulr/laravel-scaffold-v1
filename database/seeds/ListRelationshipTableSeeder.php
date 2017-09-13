<?php

use Illuminate\Database\Seeder;

class ListRelationshipTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('list_relationships')->insert([
            ['title' => 'single'],
            ['title' => 'married'],
            ['title' => 'commited'],
            ['title' => 'separate'],
            ['title' => 'divorced'],
            ['title' => 'widower']
        ]);
    }
}
