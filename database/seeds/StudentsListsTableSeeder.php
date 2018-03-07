<?php

use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class StudentsListsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('student_list_certificates')->insert([
            ['name' => 'Ventas'],
            ['name' => 'Operación de PdV'],
            ['name' => 'Administración'],
            ['name' => 'Servicio al Cliente'],
            ['name' => 'Trabajo en Equipo']
        ]);

        DB::table('student_list_genders')->insert([
            ['name' => 'Mujer'],
            ['name' => 'Hombre']
        ]);

        DB::table('student_list_payment_methods')->insert([
            ['name' => 'Transferencia Bancaria'],
            ['name' => 'CX AL punto']
        ]);

        DB::table('student_list_positions')->insert([
            ['name' => 'Operativo'],
            ['name' => 'Administrativo'],
            ['name' => 'Representante de Ventas o Asesor de Ventas'],
            ['name' => 'Jefe De Área'],
            ['name' => 'Gerente o Administrador'],
            ['name' => 'Director'],
            ['name' => 'Propietario'],
            ['name' => 'Otro']
        ]);

        DB::table('student_list_studies')->insert([
            ['name' => 'Sin estudios'],
            ['name' => 'Primaria'],
            ['name' => 'Secundaria'],
            ['name' => 'Preparatoria'],
            ['name' => 'Técnico'],
            ['name' => 'Universitario'],
            ['name' => 'Maestría']
        ]);

        // student_list_states & student_list_cities
        $statesCities = Excel::load(storage_path('app/public/states_cities.csv'), function($reader) {
            $reader->noHeading();
        }, 'ISO-8859-1')->get();

        $id = null;
        foreach ($statesCities as $key => $row) {
            if ($key != 0) {
                //info($row[0].' '.$row[1].' '.$row[2].' '.$row[4]);
                if ($row[0] != $id) {
                    $id = $row[0];
                    if ($id == 15) {
                        $row[1] = 'Edo. de México';
                    }
                    $stateId = DB::table('student_list_states')->insertGetId([
                        'name' => $row[1],
                        'short_name' => $row[2]
                    ]);
                    DB::table('student_list_cities')->insert([
                        'name' => $row[4],
                        'leader_name' => $row[6],
                        'state_id' => $stateId
                    ]);
                } else {
                    DB::table('student_list_cities')->insert([
                        'name' => $row[4],
                        'leader_name' => $row[6],
                        'state_id' => $stateId
                    ]);
                }
            }
        }

        // Advisors
        $advisors = Excel::load(storage_path('app/public/asesores.xlsx'), function($reader) {
            $reader->noHeading();
        })->get();

        foreach ($advisors as $key => $row) {
            if ($key == 0) {
                $user = App\User::create([
                    'name' => 'Fabiola Avila',
                    'email' => 'claudiafabiola.avila@ext.cemex.com',
                    'password' => Hash::make('favila53'),
                    'active' => 1,
                    'avatar' => 'avatar.png'
                ]);

                $user->roles()->attach([1]);

                $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
                Storage::put('avatars/'.$user->id.'/avatar.png', $avatar);

                $user = App\User::create([
                    'name' => 'Miguel Torres',
                    'email' => 'miguelangel.torresrs@cemex.com',
                    'password' => Hash::make('mtorres63'),
                    'active' => 1,
                    'avatar' => 'avatar.png'
                ]);

                $user->roles()->attach([1]);

                $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
                Storage::put('avatars/'.$user->id.'/avatar.png', $avatar);
            }

            if ($key != 0 && $row[0] != '') {

                $name = explode(' ', $row[0]);

                if (count($name) > 4) {
                    foreach ($name as $key => $value) {
                        if (strtolower($value) == 'de' || strtolower($value) == 'la') {
                             unset($name[$key]);
                        }
                    }
                    $name = array_values($name);
                }

                if (count($name) == 3) {
                    $lastName = $name[1];
                } else {
                    $lastName = $name[2];
                }

                $a = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
                $b = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');

                $pass = strtolower(str_replace($a, $b, substr($name[0], 0, 1).$lastName.rand(10, 99)));

                info($pass);

                $user = App\User::create([
                    'name' => $row[0],
                    'email' => $row[1],
                    'password' => Hash::make($pass),
                    'active' => 1,
                    'avatar' => 'avatar.png'
                ]);

                $user->roles()->attach([2]);

                $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
                Storage::put('avatars/'.$user->id.'/avatar.png', $avatar);

            }
        }

        // student_list_stores
        $stores = Excel::load(storage_path('app/public/stores5.xlsx'), function($reader) {
            $reader->noHeading();
        })->get();

        foreach ($stores as $key => $row) {
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
                            ->where('student_list_cities.state_id', $stateId)
                            ->where(function ($query) use ($row) {
                                $query->where('student_list_cities.name', 'like', $row[12])
                                    ->orWhere('student_list_cities.leader_name', 'like', $row[12]);
                            })->first();


                if (!$city) {
                    $city = DB::table('student_list_cities')
                                ->join('student_list_states', 'student_list_states.id', '=', 'student_list_cities.state_id')
                                ->select('student_list_cities.*', 'student_list_states.name as state')
                                ->where('student_list_cities.state_id', $stateId)
                                ->where(function ($query) use ($row) {
                                    $query->where('student_list_cities.name', 'like', '%'.$row[12].'%')
                                        ->orWhere('student_list_cities.leader_name', 'like', '%'.$row[12].'%');
                                })->first();
                }

                //info(count($city).' - '.$city.' - '.$stateId.' - '.$row[12]);

                if (!$city) {
                    //info(', notFoundCities , '.$row[7].', '.$row[14].', '.$row[12]);
                    // if ($row[7] == '65006202') {
                    //     $cityId = 41;
                    // }
                    // if ($row[7] == '65015840') {
                    //     $cityId = 2174;
                    // }
                    // if ($row[7] == '66426775') {
                    //     $cityId = 1;
                    // }
                    // if ($row[7] == '66350992') {
                    //     $cityId = 1;
                    // }
                } else {
                    $cityId = $city->id;
                }

                $user = DB::table('users')->where('email', 'like', '%'.$row[16].'%')->first();

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
                    //'store_phone' => null,
                    //'store_email' => null,

                    'holding_name' => $row[17],
                    'holding' => $row[0],
                    'rso' => $row[1],
                    'business_name' => $row[2],
                    'rfc' => $row[3],
                    'region' => $row[4],
                    'management' => $row[5],
                    'advisor_id' => $user->id
                ]);
            }
        }
    }
}
