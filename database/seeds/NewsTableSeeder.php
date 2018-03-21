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
        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-news',
                'display_name' => 'News',
                'description' => 'Read'
            ],
            [
                'name' => 'create-news',
                'display_name' => 'News',
                'description' => 'Create'
            ],
            [
                'name' => 'update-news',
                'display_name' => 'News',
                'description' => 'Update'
            ],
            [
                'name' => 'delete-news',
                'display_name' => 'News',
                'description' => 'Delete'
            ]
        ]);
    }
}
