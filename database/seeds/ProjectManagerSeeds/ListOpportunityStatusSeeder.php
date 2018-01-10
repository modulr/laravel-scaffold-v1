<?php

use Illuminate\Database\Seeder;

class ListOpportunityStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Opportunity Status
        DB::table('list_opportunity_statuses')->insert([
            [
                'id' => '4',
                'name' => 'New'
            ],
            [
                'id' => '3',
                'name' => 'Canceled'
            ],
            [
                'id' => '2',
                'name' => 'In Design'
            ],
            [
                'id' => '1',
                'name' => 'Quoted'
            ]
        ]);
    }
}
