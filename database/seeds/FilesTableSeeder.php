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
        // Module
        $moduleId = DB::table('modules')->insertGetId([
            'name' => 'files',
            'display_name' => 'Files',
            'icon' => 'mdi-folder'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-files',
                'display_name' => 'Read',
                'description' => 'Read Files',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-files',
                'display_name' => 'Create',
                'description' => 'Create Files',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-files',
                'display_name' => 'Update',
                'description' => 'Update Files',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-files',
                'display_name' => 'Delete',
                'description' => 'Delete Files',
                'module_id' => $moduleId
            ]
        ]);
    }
}
