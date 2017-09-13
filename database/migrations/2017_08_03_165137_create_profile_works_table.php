<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_works', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->integer('profession_id')->unsigned();
            $table->foreign('profession_id')->references('id')->on('list_professions');
            $table->integer('position_id')->unsigned();
            $table->foreign('position_id')->references('id')->on('list_positions');
            $table->integer('department_id')->unsigned();
            $table->foreign('department_id')->references('id')->on('list_departments');
            $table->integer('boss_id')->unsigned();
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
        Schema::dropIfExists('profile_works');
    }
}
