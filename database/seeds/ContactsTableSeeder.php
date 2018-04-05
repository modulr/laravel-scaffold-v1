<?php

use Illuminate\Database\Seeder;

class ContactsTableSeeder extends Seeder
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
            'name' => 'contacts',
            'display_name' => 'Contacts',
            'icon' => 'mdi-contacts'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-contacts',
                'display_name' => 'Read',
                'description' => 'Read Contacts',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-contacts',
                'display_name' => 'Create',
                'description' => 'Create Contacts',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-contacts',
                'display_name' => 'Update',
                'description' => 'Update Contacts',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-contacts',
                'display_name' => 'Delete',
                'description' => 'Delete Contacts',
                'module_id' => $moduleId
            ]
        ]);
    }
}
