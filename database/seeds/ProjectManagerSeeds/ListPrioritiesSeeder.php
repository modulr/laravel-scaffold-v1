<?php

use Illuminate\Database\Seeder;

class ListPrioritiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Priorities
        DB::table('list_priorities')->insert([
            [
                'id' => '1',
                'name' => 'High'
            ],
            [
                'id' => '2',
                'name' => 'Medium'
            ],
            [
                'id' => '3',
                'name' => 'Low'
            ]
        ]);
    }
}
