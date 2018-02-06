<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutopartsListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Makes
        Schema::create('autopart_list_makes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->softDeletes();
        });

        // Models
        Schema::create('autopart_list_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('make_id')->unsigned();
            $table->foreign('make_id')->references('id')->on('autopart_list_makes');
            $table->softDeletes();
        });

        // Years
        Schema::create('autopart_list_years', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->softDeletes();
        });

        // Status
        Schema::create('autopart_status', function (Blueprint $table) {
            $table->increments('id')->comment = "The status ID";
            $table->string('name')->unique()->comment = "Name status ID";
            $table->string('display_name')->nullable()->comment = "Display name of status";
            $table->string('description')->nullable()->comment = "Long-form description";
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('autopart_list_makes');
        Schema::dropIfExists('autopart_list_models');
        Schema::dropIfExists('autopart_list_years');
        Schema::dropIfExists('autopart_status');
    }
}
