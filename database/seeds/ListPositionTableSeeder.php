<?php

use Illuminate\Database\Seeder;

class ListPositionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('list_positions')->insert([
            ['title' => 'SEO'],
            ['title' => 'Digital Marketing Manager'],
            ['title' => 'Social Media Manager'],
            ['title' => 'Scrum Master'],
            ['title' => 'Product Owner'],
            ['title' => 'Social Media + Marketing'],
            ['title' => 'Frontend Developer'],
            ['title' => 'Backend Developer'],
            ['title' => 'Full-stack Developer'],
            ['title' => 'UX Designer'],
            ['title' => 'UI Designer'],
            ['title' => 'Devops Manager']

        ]);
    }
}
