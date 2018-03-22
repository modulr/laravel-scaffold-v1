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
        // Modules
        $this->call(AutopartsTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(FilesTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(TasksTableSeeder::class);

        // Core Modules
        $this->call(DashboardTableSeeder::class);
        $this->call(ProfileTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
