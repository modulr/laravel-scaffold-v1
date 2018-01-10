<?php

use Illuminate\Database\Seeder;

class ListCurrenciesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Currencies
        DB::table('list_type_currencies')->insert([
            [
                'title' => 'MXN',
                'exchange_rate' => 1
            ],
            [
                'title' => 'USD',
                'exchange_rate' => 17.50
            ]
        ]);
    }
}
