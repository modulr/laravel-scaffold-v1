<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('roles')->insert([
            [
                'id' => '1',
                'name' => 'Admin'
            ],
            [
                'id' => '2',
                'name' => 'User'
            ],
            [
                'id' => '3',
                'name' => 'Guest'
            ]
        ]);

        DB::table('Priority')->insert([
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

        factory(App\User::class, 10)
            ->create()
            ->each(function ($u) {

                $avatar = Avatar::create($u->name)->getImageObject()->encode('png');
                Storage::put('avatars/'.$u->id.'/avatar.png', $avatar);

                $u->news()->saveMany(factory(App\News::class, 3)->make());
                $u->tasks()->saveMany(factory(App\Task::class, 3)->make());
            });
    }
}
