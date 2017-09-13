<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilePersonalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_personals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->date('birthday')->nullable();
            $table->string('place_of_birth')->nullable();
            $table->integer('gender_id')->unsigned();
            $table->foreign('gender_id')->references('id')->on('list_genders');
            $table->integer('relationship_id')->unsigned();
            $table->foreign('relationship_id')->references('id')->on('list_relationships');
            $table->string('rfc')->nullable();
            $table->string('curp')->nullable();
            $table->string('nss')->nullable();
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
    }
}
