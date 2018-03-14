<?php

use Illuminate\Database\Seeder;

class FakerDataSeeder extends Seeder
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
                Storage::put('avatars/'.$u->id.'/avatar.png', (string) $avatar);

                $u->profilePersonal()->save(factory(App\Models\Profile\ProfilePersonal::class)->make());
                $u->profileContact()->saveMany(factory(App\Models\Profile\ProfileContact::class, 3)->make());
                $u->profileEducation()->saveMany(factory(App\Models\Profile\ProfileEducation::class, 3)->make());
                $u->profileFamily()->saveMany(factory(App\Models\Profile\ProfileFamily::class, 3)->make());
                $u->profilePlace()->saveMany(factory(App\Models\Profile\ProfilePlace::class, 3)->make());
                $u->profileWork()->save(factory(App\Models\Profile\ProfileWork::class)->make());

                $u->news()->saveMany(factory(App\Models\News\News::class, 3)->make());

                $u->tasks()->saveMany(factory(App\Models\Tasks\Task::class, 3)->make());

                $u->events()->saveMany(factory(App\Models\Events\Event::class, 3)->make());

                $u->autoparts()->saveMany(factory(App\Models\Autoparts\Autopart::class, 13)->make());
            });
    }
}
