<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ProfileListsTableSeeder::class);
        $this->call(ProjectManagerSeeder::class);
        $this->call(LaratrustSeeder::class);
    }
}
