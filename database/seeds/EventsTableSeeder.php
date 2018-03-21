<?php

use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-events',
                'display_name' => 'Events',
                'description' => 'Read'
            ],
            [
                'name' => 'create-events',
                'display_name' => 'Events',
                'description' => 'Create'
            ],
            [
                'name' => 'update-events',
                'display_name' => 'Events',
                'description' => 'Update'
            ],
            [
                'name' => 'delete-events',
                'display_name' => 'Events',
                'description' => 'Delete'
            ]
        ]);
    }
}
