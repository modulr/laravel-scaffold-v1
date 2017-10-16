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
        $this->call(ListGenderTableSeeder::class);
        $this->call(ListRelationshipTableSeeder::class);
        $this->call(ListRelationTableSeeder::class);
        $this->call(ListContactTableSeeder::class);
        $this->call(ListProfessionTableSeeder::class);
        $this->call(ListPositionTableSeeder::class);
        $this->call(ListDepartmentTableSeeder::class);
        $this->call(ListTypeEmployeesSeeder::class);
        $this->call(ListAreasTableSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(PrioritiesSeeder::class);
        $this->call(ListStatusQuoteSeeder::class);
        $this->call(ListTypeServiceSeeder::class);
        $this->call(ListTypeCurrencySeeder::class);

        factory(App\User::class, 10)
            ->create()
            ->each(function ($u) {

                $avatar = Avatar::create($u->name)->getImageObject()->encode('png');
                Storage::put('avatars/'.$u->id.'/avatar.png', $avatar);

                $u->profilePersonal()->save(factory(App\Models\Profile\ProfilePersonal::class)->make());
                $u->profileContact()->saveMany(factory(App\Models\Profile\ProfileContact::class, 3)->make());
                $u->profileEducation()->saveMany(factory(App\Models\Profile\ProfileEducation::class, 3)->make());
                $u->profileFamily()->saveMany(factory(App\Models\Profile\ProfileFamily::class, 3)->make());
                $u->profilePlace()->saveMany(factory(App\Models\Profile\ProfilePlace::class, 3)->make());

                $u->profileWork()->save(factory(App\Models\Profile\ProfileWork::class)->make());

                $u->news()->saveMany(factory(App\News::class, 3)->make());

                $u->tasks()->saveMany(factory(App\Task::class, 3)->make());
            });
    }
}
