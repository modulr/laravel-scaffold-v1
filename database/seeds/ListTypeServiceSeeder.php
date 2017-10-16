<?php

use Illuminate\Database\Seeder;

class ListTypeServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      DB::table('list_type_services')->insert([
          ['title' => 'Redes'],
          ['title' => 'Cableado'],
          ['title' => 'Seguridad y Acceso'],
          ['title' => 'Videoconferencia'],
          ['title' => 'Energía y Enfriamiento'],
          ['title' => 'Sistemas AV'],
          ['title' => 'Tecnologías  de Información'],
      ]);
    }
}
