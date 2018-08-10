<?php

use Illuminate\Database\Seeder;

class ProfileListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Lists
        DB::table('profile_list_contacts')->insert([
            ['title' => 'mail'],
            ['title' => 'phone'],
            ['title' => 'cellphone']
        ]);
    }
}
