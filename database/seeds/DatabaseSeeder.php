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
        factory(App\User::class, 10)
            ->create()
            ->each(function ($u) {
                $u->news()->saveMany(factory(App\News::class, 3)->make());
                $u->tasks()->saveMany(factory(App\Task::class, 3)->make());
            });
    }
}
