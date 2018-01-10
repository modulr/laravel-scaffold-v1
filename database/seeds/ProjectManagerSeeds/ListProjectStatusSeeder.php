<?php

use Illuminate\Database\Seeder;

class ListProjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Project Status
        DB::table('list_project_statuses')->insert([
            [
                'id' => '1',
                'name' => 'Done'
            ],
            [
                'id' => '2',
                'name' => 'In Progress'
            ],
            [
                'id' => '3',
                'name' => 'Canceled'
            ],
            [
                'id' => '4',
                'name' => 'New'
            ]
        ]);
    }
}
