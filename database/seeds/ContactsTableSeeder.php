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
        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-contacts',
                'display_name' => 'Contacts',
                'description' => 'Read'
            ],
            [
                'name' => 'create-contacts',
                'display_name' => 'Contacts',
                'description' => 'Create'
            ],
            [
                'name' => 'update-contacts',
                'display_name' => 'Contacts',
                'description' => 'Update'
            ],
            [
                'name' => 'delete-contacts',
                'display_name' => 'Contacts',
                'description' => 'Delete'
            ]
        ]);
    }
}
