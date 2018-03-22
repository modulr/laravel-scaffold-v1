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
        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'events',
            'display_name' => 'Events',
            'icon' => 'mdi-event'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-events',
                'display_name' => 'Read',
                'description' => 'Read Events',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-events',
                'display_name' => 'Create',
                'description' => 'Create Events',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-events',
                'display_name' => 'Update',
                'description' => 'Update Events',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-events',
                'display_name' => 'Delete',
                'description' => 'Delete Events',
                'module_id' => $moduleId
            ]
        ]);
    }
}
