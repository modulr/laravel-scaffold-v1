<?php

use Illuminate\Database\Seeder;
use App\Models\Customers\Customer;

class PMClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Customers\Client::class, 10)
            ->create()
            ->each(function ($c) {
                $c->customers()->save(factory(App\Models\Customers\Customer::class)
                ->make());
            });
    }
}
