<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id')->comment = "Student ID";
            $table->string('name')->comment = "Student name";
            $table->string('last_name')->comment = "Student last name";
            $table->date('birthday')->nullable()->comment = "Student last birthday";
            $table->integer('gender_id')->unsigned()->nullable();
            $table->foreign('gender_id')->references('id')->on('student_list_genders');
            $table->integer('study_id')->unsigned()->nullable();
            $table->foreign('study_id')->references('id')->on('student_list_studies');
            $table->integer('position_id')->unsigned()->nullable();
            $table->foreign('position_id')->references('id')->on('student_list_positions');
            $table->string('years_in_position')->comment = "years in position";
            $table->integer('payment_method_id')->unsigned()->nullable();
            $table->foreign('payment_method_id')->references('id')->on('student_list_payment_methods');
            $table->integer('certificate_id')->unsigned()->nullable();
            $table->foreign('certificate_id')->references('id')->on('student_list_certificates');
            $table->boolean('have_studied')->default(false)->comment = "have you studied before at the Construrama university?";
            $table->string('username')->nullable()->comment = "Student username";

            $table->string('phone')->comment = "Student phone";
            $table->string('cellphone')->comment = "Student cellphone";
            $table->string('email')->comment = "Student email";

            $table->integer('store_id')->unsigned()->nullable();
            $table->foreign('store_id')->references('id')->on('student_list_stores');

            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
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
        Schema::dropIfExists('students');
    }
}
