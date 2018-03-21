<?php

use Illuminate\Database\Seeder;

class TasksTableSeeder extends Seeder
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
                'name' => 'read-tasks',
                'display_name' => 'Tasks',
                'description' => 'Read'
            ],
            [
                'name' => 'create-tasks',
                'display_name' => 'Tasks',
                'description' => 'Create'
            ],
            [
                'name' => 'update-tasks',
                'display_name' => 'Tasks',
                'description' => 'Update'
            ],
            [
                'name' => 'delete-tasks',
                'display_name' => 'Tasks',
                'description' => 'Delete'
            ]
        ]);
    }
}
