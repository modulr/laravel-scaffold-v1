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
        // Lists
        Schema::create('profile_list_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->softDeletes();
        });
        // Profile
        Schema::create('profile_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('contact');
            $table->integer('type_id')->unsigned();
            $table->foreign('type_id')->references('id')->on('profile_list_contacts');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('profile_places', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('place');
            $table->boolean('currently')->default(0);
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
        // Lists
        Schema::dropIfExists('profile_list_contacts');
        // Profile
        Schema::dropIfExists('profile_contacts');
        Schema::dropIfExists('profile_places');
    }
}
