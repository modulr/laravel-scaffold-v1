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
        $this->call(ProfileTableSeeder::class);
        $this->call(AutopartsTableSeeder::class);
        $this->call(ContactsTableSeeder::class);
        $this->call(EventsTableSeeder::class);
        $this->call(FilesTableSeeder::class);
        $this->call(NewsTableSeeder::class);
        $this->call(TasksTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
