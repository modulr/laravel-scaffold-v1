<?php

use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'id' => '1',
                'title' => 'Admin'
            ],
            [
                'id' => '2',
                'title' => 'User'
            ],
            [
                'id' => '3',
                'title' => 'Guest'
            ]
        ]);
    }
}
