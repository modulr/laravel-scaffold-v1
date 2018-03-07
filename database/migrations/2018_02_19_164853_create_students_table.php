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
            $table->date('birthday')->comment = "Student last birthday";
            $table->integer('gender_id')->unsigned();
            $table->foreign('gender_id')->references('id')->on('student_list_genders');
            $table->integer('study_id')->unsigned();
            $table->foreign('study_id')->references('id')->on('student_list_studies');
            $table->integer('position_id')->unsigned();
            $table->foreign('position_id')->references('id')->on('student_list_positions');
            $table->string('years_in_position')->comment = "years in position";
            $table->integer('payment_method_id')->unsigned();
            $table->foreign('payment_method_id')->references('id')->on('student_list_payment_methods');
            $table->integer('certificate_id')->unsigned();
            $table->foreign('certificate_id')->references('id')->on('student_list_certificates');
            $table->boolean('have_studied')->default(false)->comment = "have you studied before at the Construrama university?";
            $table->string('username')->nullable()->default(null)->comment = "Student username";

            $table->string('phone')->comment = "Student phone";
            $table->string('cellphone')->comment = "Student cellphone";
            $table->string('email')->comment = "Student email";

            $table->integer('store_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('student_list_stores');

            $table->boolean('verified')->default(false)->comment = "Is verified the student info?";

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
