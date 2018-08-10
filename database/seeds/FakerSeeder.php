<?php

use Illuminate\Database\Seeder;

class FakerSeeder extends Seeder
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
                $avatar = Avatar::create($u->name)->getImageObject()->encode('png');
                Storage::put('avatars/'.$u->id.'/avatar.png', $avatar);

                $u->profileContact()->saveMany(factory(App\Models\Profile\ProfileContact::class, 3)->make());
                $u->profilePlace()->saveMany(factory(App\Models\Profile\ProfilePlace::class, 3)->make());

                $u->saucers()->saveMany(factory(App\Models\Stb\Saucer::class, 3)->make());
            });
    }
}
