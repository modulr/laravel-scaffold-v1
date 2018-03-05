<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_list_certificates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('student_list_genders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('student_list_payment_methods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('student_list_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('student_list_studies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('student_list_states', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('short_name');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('student_list_cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('leader_name')->nullable();
            $table->integer('state_id')->unsigned()->nullable();
            $table->foreign('state_id')->references('id')->on('student_list_states');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('student_list_stores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('store_id');
            $table->string('name');
            $table->string('street');
            $table->string('number');
            $table->string('colony');
            $table->string('cp');
            $table->integer('city_id')->unsigned()->nullable();
            $table->foreign('city_id')->references('id')->on('student_list_cities');
            $table->integer('state_id')->unsigned();
            $table->foreign('state_id')->references('id')->on('student_list_states');
            $table->string('phone');
            $table->string('store_phone')->nullable();
            $table->string('store_email')->nullable();
            $table->string('holding_name');
            $table->integer('holding');
            $table->integer('rso');
            $table->string('business_name');
            $table->string('rfc');
            $table->string('region');
            $table->string('management');
            $table->integer('advisor_id')->unsigned()->nullable();
            $table->foreign('advisor_id')->references('id')->on('users');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('student_list_certificates');
        Schema::dropIfExists('student_list_genders');
        Schema::dropIfExists('student_list_payment_methods');
        Schema::dropIfExists('student_list_positions');
        Schema::dropIfExists('student_list_studies');
        Schema::dropIfExists('student_list_stores');
        Schema::dropIfExists('student_list_cities');
        Schema::dropIfExists('student_list_states');
    }
}
