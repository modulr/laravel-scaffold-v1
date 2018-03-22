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
        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'tasks',
            'display_name' => 'Tasks',
            'icon' => 'mdi-assignment'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-tasks',
                'display_name' => 'Read',
                'description' => 'Read Tasks',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-tasks',
                'display_name' => 'Create',
                'description' => 'Create Tasks',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-tasks',
                'display_name' => 'Update',
                'description' => 'Update Tasks',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-tasks',
                'display_name' => 'Delete',
                'description' => 'Delete Tasks',
                'module_id' => $moduleId
            ]
        ]);
    }
}
