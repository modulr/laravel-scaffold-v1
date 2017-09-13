<?php

use Illuminate\Database\Seeder;

class PrioritiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('priorities')->insert([
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
