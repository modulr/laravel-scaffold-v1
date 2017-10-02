<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['title' => 'Admin', 'description' => 'All access', 'lock' => true],
            ['title' => 'User', 'description' => 'Basic access', 'lock' => true],
            ['title' => 'Guest', 'description' => 'Read access', 'lock' => true]
        ]);
    }
}
