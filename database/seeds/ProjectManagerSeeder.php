<?php

use Illuminate\Database\Seeder;

class ProjectManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Areas
        DB::table('list_areas')->insert([
            ['title' => 'Norte'],
            ['title' => 'Centro'],
            ['title' => 'Sur']
        ]);
        // Quote Status
        DB::table('list_status_quotes')->insert([
            [
                'id' => '1',
                'title' => 'Pending'
            ],
            [
                'id' => '2',
                'title' => 'Sent'
            ],
            [
                'id' => '3',
                'title' => 'Approve'
            ],
            [
                'id' => '4',
                'title' => 'Refuse'
            ],
        ]);
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
        // Employees
        DB::table('list_type_employees')->insert([
            ['title' => 'Designer'],
            ['title' => 'Salesman']
        ]);
        // Services
        DB::table('list_type_services')->insert([
            ['title' => 'Redes'],
            ['title' => 'Cableado'],
            ['title' => 'Seguridad y Acceso'],
            ['title' => 'Videoconferencia'],
            ['title' => 'Energía y Enfriamiento'],
            ['title' => 'Sistemas AV'],
            ['title' => 'Tecnologías  de Información'],
        ]);
        // Priorities
        DB::table('list_priorities')->insert([
            [
                'id' => '1',
                'name' => 'High'
            ],
            [
                'id' => '2',
                'name' => 'Medium'
            ],
            [
                'id' => '3',
                'name' => 'Low'
            ]
        ]);
                // Project Status
        DB::table('list_project_statuses')->insert([
            [
                'id' => '1',
                'name' => 'Done'
            ],
            [
                'id' => '2',
                'name' => 'In Progress'
            ],
            [
                'id' => '3',
                'name' => 'Canceled'
            ],
            [
                'id' => '4',
                'name' => 'New'
            ]
        ]);
    }
}