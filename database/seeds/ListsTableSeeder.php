<?php

use Illuminate\Database\Seeder;

class ListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Genders
        DB::table('list_genders')->insert([
            ['title' => 'female'],
            ['title' => 'male']
        ]);
        // Relationsships
        DB::table('list_relationships')->insert([
            ['title' => 'single'],
            ['title' => 'married'],
            ['title' => 'commited'],
            ['title' => 'separate'],
            ['title' => 'divorced'],
            ['title' => 'widower']
        ]);
        // Relations
        DB::table('list_relations')->insert([
            ['title' => 'father'],
            ['title' => 'mother'],
            ['title' => 'son'],
            ['title' => 'daughter'],
            ['title' => 'brother'],
            ['title' => 'sister'],
            ['title' => 'wife'],
            ['title' => 'husband'],
        ]);
        // Contacts
        DB::table('list_contacts')->insert([
            ['title' => 'mail'],
            ['title' => 'phone'],
            ['title' => 'cellphone']
        ]);
        // Professions
        DB::table('list_professions')->insert([
            ['title' => 'Developer'],
            ['title' => 'Designer'],
            ['title' => 'Software Architect']
        ]);
        // Positions
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
        // Departments
        DB::table('list_departments')->insert([
            ['title' => 'Accounting and Finance'],
            ['title' => 'Human Resource Management'],
            ['title' => 'Marketing'],
            ['title' => 'Purchasing'],
            ['title' => 'Production'],
            ['title' => 'Research and Development'],
        ]);
    }
}
