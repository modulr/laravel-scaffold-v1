<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Modules
        $usersId = DB::table('modules')->insertGetId([
            'name' => 'users',
            'display_name' => 'Users',
            'icon' => 'mdi-people'
        ]);
        $rolesId = DB::table('modules')->insertGetId([
            'name' => 'roles',
            'display_name' => 'Roles',
            'icon' => 'mdi-vpn-key'
        ]);

        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-users',
                'display_name' => 'Read',
                'description' => 'Read Users',
                'module_id' => $usersId
            ],
            [
                'name' => 'create-users',
                'display_name' => 'Create',
                'description' => 'Create Users',
                'module_id' => $usersId
            ],
            [
                'name' => 'update-users',
                'display_name' => 'Update',
                'description' => 'Update Users',
                'module_id' => $usersId
            ],
            [
                'name' => 'delete-users',
                'display_name' => 'Delete',
                'description' => 'Delete Users',
                'module_id' => $usersId
            ],
            [
                'name' => 'read-roles',
                'display_name' => 'Read',
                'description' => 'Read Roles',
                'module_id' => $rolesId
            ],
            [
                'name' => 'create-roles',
                'display_name' => 'Create',
                'description' => 'Create Roles',
                'module_id' => $rolesId
            ],
            [
                'name' => 'update-roles',
                'display_name' => 'Update',
                'description' => 'Update Roles',
                'module_id' => $rolesId
            ],
            [
                'name' => 'delete-roles',
                'display_name' => 'Delete',
                'description' => 'Delete Roles',
                'module_id' => $rolesId
            ]
        ]);

        // Roles
        $role = \App\Role::create([
            'name' => 'admin',
            'display_name' => 'Admin',
            'description' => 'Admin'
        ]);

        // Get all permissions
        $permissions = \App\Permission::get();

        // Attach all permissions to the role
        $role->permissions()->sync($permissions);

        // Create default user for each role
        $user = \App\User::create([
            'name' => 'admin',
            'email' => 'admin@modulr.io',
            'password' => bcrypt('admin')
        ]);

        $avatar = Avatar::create($user->name)->getImageObject()->encode('png');
        Storage::put('avatars/'.$user->id.'/avatar.png', (string) $avatar);

        \App\Models\Profile\ProfilePersonal::create([
            'user_id' => $user->id
        ]);

        \App\Models\Profile\ProfileWork::create([
            'user_id' => $user->id
        ]);

        $user->attachRole($role);
    }
}
