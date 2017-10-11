<?php

use Illuminate\Database\Seeder;

class GroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_groups')->insert([
            ['title' => 'Admin', 'description' => 'All access', 'lock' => true],
            ['title' => 'User', 'description' => 'Basic access', 'lock' => true],
            ['title' => 'Guest', 'description' => 'Read access', 'lock' => true]
        ]);
    }
}
