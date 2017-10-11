<?php

use Illuminate\Database\Seeder;

class ListRelationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('list_relations')->insert([
            ['title' => 'father'],
            ['title' => 'mother'],
            ['title' => 'son'],
            ['title' => 'daughter'],
            ['title' => 'brother'],
            ['title' => 'sister'],
            ['title' => 'wife'],
            ['title' => 'husband'],
        ]);
    }
}
