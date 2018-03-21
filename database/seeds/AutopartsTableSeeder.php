<?php

use Illuminate\Database\Seeder;

class AutopartsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-sales',
                'display_name' => 'Sales',
                'description' => 'Read'
            ],
            [
                'name' => 'create-sales',
                'display_name' => 'Sales',
                'description' => 'Create'
            ],
            [
                'name' => 'update-sales',
                'display_name' => 'Sales',
                'description' => 'Update'
            ],
            [
                'name' => 'delete-sales',
                'display_name' => 'Sales',
                'description' => 'Delete'
            ],
            [
                'name' => 'read-inventory',
                'display_name' => 'Inventory',
                'description' => 'Read'
            ],
            [
                'name' => 'create-inventory',
                'display_name' => 'Inventory',
                'description' => 'Create'
            ],
            [
                'name' => 'update-inventory',
                'display_name' => 'Inventory',
                'description' => 'Update'
            ],
            [
                'name' => 'delete-inventory',
                'display_name' => 'Inventory',
                'description' => 'Delete'
            ],
            [
                'name' => 'read-inventory-lists',
                'display_name' => 'Inventory Lists',
                'description' => 'Read'
            ],
            [
                'name' => 'create-inventory-lists',
                'display_name' => 'Inventory Lists',
                'description' => 'Create'
            ],
            [
                'name' => 'update-inventory-lists',
                'display_name' => 'Inventory Lists',
                'description' => 'Update'
            ],
            [
                'name' => 'delete-inventory-lists',
                'display_name' => 'Inventory Lists',
                'description' => 'Delete'
            ]
        ]);

        // Lists
        DB::table('autopart_list_makes')->insert([
            ['name' => 'Aston Martin'],
            ['name' => 'Audi'],
            ['name' => 'Bentley'],
            ['name' => 'BMW'],
            ['name' => 'Bugatti'],
            ['name' => 'Ferrari'],
            ['name' => 'Lamborghini'],
            ['name' => 'Maserati'],
            ['name' => 'Mercedes Benz'],
            ['name' => 'Porsche'],
            ['name' => 'Tesla']
        ]);

        DB::table('autopart_list_models')->insert([
            ['name' => 'A1', 'make_id' => 2],
            ['name' => 'A3', 'make_id' => 2],
            ['name' => 'A4', 'make_id' => 2],
            ['name' => 'A5', 'make_id' => 2],
            ['name' => 'A6', 'make_id' => 2],
            ['name' => 'A7', 'make_id' => 2],
            ['name' => 'M1', 'make_id' => 4],
            ['name' => 'M2', 'make_id' => 4],
            ['name' => 'M3', 'make_id' => 4],
            ['name' => 'M4', 'make_id' => 4],
            ['name' => 'M5', 'make_id' => 4],
            ['name' => 'M6', 'make_id' => 4]
        ]);

        DB::table('autopart_list_origins')->insert([
            ['name' => 'New'],
            ['name' => 'Used'],
            ['name' => 'Generic']
        ]);

        DB::table('autopart_list_status')->insert([
            ['name' => 'Available'],
            ['name' => 'Not available'],
            ['name' => 'Separated'],
            ['name' => 'Sold']
        ]);

        DB::table('autopart_list_years')->insert([
            ['name' => '2018'],
            ['name' => '2017'],
            ['name' => '2016'],
            ['name' => '2015'],
            ['name' => '2014'],
            ['name' => '2013'],
            ['name' => '2012'],
            ['name' => '2011'],
            ['name' => '2010']
        ]);
    }
}
