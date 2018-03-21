<?php

use Illuminate\Database\Seeder;

class FilesTableSeeder extends Seeder
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
                'name' => 'read-files',
                'display_name' => 'Files',
                'description' => 'Read'
            ],
            [
                'name' => 'create-files',
                'display_name' => 'Files',
                'description' => 'Create'
            ],
            [
                'name' => 'update-files',
                'display_name' => 'Files',
                'description' => 'Update'
            ],
            [
                'name' => 'delete-files',
                'display_name' => 'Files',
                'description' => 'Delete'
            ]
        ]);
    }
}
