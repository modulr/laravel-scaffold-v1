<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Personals
        Schema::create('profile_personals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->date('birthday')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->integer('gender_id')->unsigned()->nullable();
            $table->foreign('gender_id')->references('id')->on('list_genders');
            $table->integer('relationship_id')->unsigned()->nullable();
            $table->foreign('relationship_id')->references('id')->on('list_relationships');
            $table->string('rfc')->nullable();
            $table->string('curp')->nullable();
            $table->string('nss')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        // Contacts
        Schema::create('profile_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('contact');
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('id')->on('list_contacts');
            $table->timestamps();
            $table->softDeletes();
        });
        // Educations
        Schema::create('profile_educations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('title');
            $table->string('school_name');
            $table->integer('start_year');
            $table->integer('end_year');
            $table->timestamps();
            $table->softDeletes();
        });
        // Families
        Schema::create('profile_families', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->integer('gender_id')->unsigned();
            $table->foreign('gender_id')->references('id')->on('list_genders');
            $table->integer('relation_id')->unsigned();
            $table->foreign('relation_id')->references('id')->on('list_relations');
            $table->date('birthday')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        // Places
        Schema::create('profile_places', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('place');
            $table->boolean('currently')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        // Works
        Schema::create('profile_works', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('profession_id')->unsigned()->nullable();
            $table->foreign('profession_id')->references('id')->on('list_professions');
            $table->integer('position_id')->unsigned()->nullable();
            $table->foreign('position_id')->references('id')->on('list_positions');
            $table->integer('department_id')->unsigned()->nullable();
            $table->foreign('department_id')->references('id')->on('list_departments');
            $table->integer('boss_id')->unsigned()->nullable();
            $table->foreign('boss_id')->references('id')->on('users');
            $table->date('starting_from')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_personals');
        Schema::dropIfExists('profile_contacts');
        Schema::dropIfExists('profile_educations');
        Schema::dropIfExists('profile_families');
        Schema::dropIfExists('profile_places');
        Schema::dropIfExists('profile_works');
    }
}
