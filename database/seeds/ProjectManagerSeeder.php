<?php

use Illuminate\Database\Seeder;
use App\Models\Projects\ListOpportunityStatus;

class ProjectManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ListAreasSeeder::class);
        $this->call(ListCurrenciesSeeder::class);
        $this->call(ListEmployeesSeeder::class);
        $this->call(ListOpportunityStatusSeeder::class);
        $this->call(ListPrioritiesSeeder::class);
        $this->call(ListProjectStatusSeeder::class);
        $this->call(ListServicesSeeder::class);
        $this->call(ListStatusQuotesSeeder::class);
    }
}