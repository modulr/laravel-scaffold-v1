<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Genders
        Schema::create('list_genders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->softDeletes();
        });
        // Relationsships
        Schema::create('list_relationships', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->softDeletes();
        });
        // Relations
        Schema::create('list_relations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->softDeletes();
        });
        // Contacts
        Schema::create('list_contacts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->softDeletes();
        });
        // Professions
        Schema::create('list_professions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->softDeletes();
        });
        // Positions
        Schema::create('list_positions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->softDeletes();
        });
        // Departments
        Schema::create('list_departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
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
        Schema::dropIfExists('list_genders');
        Schema::dropIfExists('list_relationships');
        Schema::dropIfExists('list_relations');
        Schema::dropIfExists('list_contacts');
        Schema::dropIfExists('list_professions');
        Schema::dropIfExists('list_positions');
        Schema::dropIfExists('list_departments');
    }
}
