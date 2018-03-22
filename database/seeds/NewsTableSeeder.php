<?php

use Illuminate\Database\Seeder;

class NewsTableSeeder extends Seeder
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
            'name' => 'news',
            'display_name' => 'News',
            'icon' => 'mdi-whatshot'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-news',
                'display_name' => 'Read',
                'description' => 'Read News',
                'module_id' => $moduleId
            ],
            [
                'name' => 'create-news',
                'display_name' => 'Create',
                'description' => 'Create News',
                'module_id' => $moduleId
            ],
            [
                'name' => 'update-news',
                'display_name' => 'Update',
                'description' => 'Update News',
                'module_id' => $moduleId
            ],
            [
                'name' => 'delete-news',
                'display_name' => 'Delete',
                'description' => 'Delete News',
                'module_id' => $moduleId
            ]
        ]);
    }
}
