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
        // Permissions
        DB::table('permissions')->insert([
            [
                'name' => 'read-users',
                'display_name' => 'Users',
                'description' => 'Read'
            ],
            [
                'name' => 'create-users',
                'display_name' => 'Users',
                'description' => 'Create'
            ],
            [
                'name' => 'update-users',
                'display_name' => 'Users',
                'description' => 'Update'
            ],
            [
                'name' => 'delete-users',
                'display_name' => 'Users',
                'description' => 'Delete'
            ],
            [
                'name' => 'read-roles',
                'display_name' => 'Roles',
                'description' => 'Read'
            ],
            [
                'name' => 'create-roles',
                'display_name' => 'Roles',
                'description' => 'Create'
            ],
            [
                'name' => 'update-roles',
                'display_name' => 'Roles',
                'description' => 'Update'
            ],
            [
                'name' => 'delete-roles',
                'display_name' => 'Roles',
                'description' => 'Delete'
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
            'password' => bcrypt('secret')
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
