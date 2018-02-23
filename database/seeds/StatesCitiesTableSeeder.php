<?php

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class StatesCitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $result = Excel::load(storage_path('app/public/stores.xlsx'), function($reader) {
            $reader->noHeading();
        })->get();

        $x = 0;
        foreach ($result as $key => $row) {
            if ($key != 0) {
                $state = DB::table('student_list_states')->where('name', 'like', '%'.$row[14].'%')->first();
                if (!$state) {
                    //info(', notFoundStates , '.$row[7].', '.$row[14].', '.$row[12]);
                    if ($row[14] == 'Estado de México') {
                        $stateId = 15;
                    }
                } else {
                    $stateId = $state->id;
                }

                $city = DB::table('student_list_cities')
                            ->join('student_list_states', 'student_list_states.id', '=', 'student_list_cities.state_id')
                            ->select('student_list_cities.*', 'student_list_states.name as state')
                            ->where('student_list_cities.name', 'like', '%'.$row[12].'%')
                            ->orWhere('student_list_cities.leader_name', 'like', '%'.$row[12].'%')
                            ->where('student_list_cities.state_id', $stateId)
                            ->first();
                if (!$city) {
                    //info(', notFoundCities , '.$row[7].', '.$row[14].', '.$row[12]);
                    $cityId = 1;
                } else {
                    $cityId = $city->id;
                }

                DB::table('student_list_stores')->insert([
                    'store_id' => $row[7],
                    'name' => $row[8],
                    'street' => $row[9],
                    'number' => $row[10],
                    'colony' => $row[11],
                    'cp' => $row[13],
                    'city_id' => $cityId,
                    'state_id' => $stateId,
                    'phone' => $row[15],

                    'holding' => $row[0],
                    'rso' => $row[1],
                    'business_name' => $row[2],
                    'rfc' => $row[3],
                    'region' => $row[4],
                    'management' => $row[5],
                    'seller_name' => $row[6],
                    'seller_email' => 'por llenar'
                ]);
            }
        }
    }
}
