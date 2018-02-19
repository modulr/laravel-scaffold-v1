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
        $this->call(AutopartsListsTableSeeder::class);
        $this->call(ProfileListsTableSeeder::class);
        $this->call(LaratrustSeeder::class);
    }
}
