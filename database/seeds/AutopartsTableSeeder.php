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
        // Modules
        $salesId = DB::table('modules')->insertGetId([
            'name' => 'sales',
            'display_name' => 'Sales',
            'icon' => 'mdi-monetization-on'
        ]);
        $inventoryId = DB::table('modules')->insertGetId([
            'name' => 'inventory',
            'display_name' => 'Inventory',
            'icon' => 'mdi-widgets'
        ]);
        $inventoryListsId = DB::table('modules')->insertGetId([
            'name' => 'inventory-lists',
            'display_name' => 'Inventory Lists',
            'icon' => 'mdi-list'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-sales',
                'display_name' => 'Read',
                'description' => 'Read Sales',
                'module_id' => $salesId
            ],
            [
                'name' => 'create-sales',
                'display_name' => 'Create',
                'description' => 'Create Sales',
                'module_id' => $salesId
            ],
            [
                'name' => 'update-sales',
                'display_name' => 'Update',
                'description' => 'Update Sales',
                'module_id' => $salesId
            ],
            [
                'name' => 'delete-sales',
                'display_name' => 'Delete',
                'description' => 'Delete Sales',
                'module_id' => $salesId
            ],
            [
                'name' => 'read-inventory',
                'display_name' => 'Read',
                'description' => 'Read Inventory',
                'module_id' => $inventoryId
            ],
            [
                'name' => 'create-inventory',
                'display_name' => 'Create',
                'description' => 'Create Inventory',
                'module_id' => $inventoryId
            ],
            [
                'name' => 'update-inventory',
                'display_name' => 'Update',
                'description' => 'Update Inventory',
                'module_id' => $inventoryId
            ],
            [
                'name' => 'delete-inventory',
                'display_name' => 'Delete',
                'description' => 'Delete Inventory',
                'module_id' => $inventoryId
            ],
            [
                'name' => 'read-inventory-lists',
                'display_name' => 'Read',
                'description' => 'Read Inventory Lists',
                'module_id' => $inventoryListsId
            ],
            [
                'name' => 'create-inventory-lists',
                'display_name' => 'Create',
                'description' => 'Create Inventory Lists',
                'module_id' => $inventoryListsId
            ],
            [
                'name' => 'update-inventory-lists',
                'display_name' => 'Update',
                'description' => 'Update Inventory Lists',
                'module_id' => $inventoryListsId
            ],
            [
                'name' => 'delete-inventory-lists',
                'display_name' => 'Delete',
                'description' => 'Delete Inventory Lists',
                'module_id' => $inventoryListsId
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
