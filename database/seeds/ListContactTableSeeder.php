<?php

use Illuminate\Database\Seeder;

class ListContactTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('list_contacts')->insert([
            ['title' => 'mail'],
            ['title' => 'phone'],
            ['title' => 'cellphone']
        ]);
    }
}
