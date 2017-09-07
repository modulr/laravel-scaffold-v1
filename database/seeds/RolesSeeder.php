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
                'name' => 'Admin'
            ],
            [
                'id' => '2',
                'name' => 'User'
            ],
            [
                'id' => '3',
                'name' => 'Guest'
            ]
        ]);
    }
}
