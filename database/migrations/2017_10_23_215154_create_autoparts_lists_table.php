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
        Schema::create('autopart_list_makes', function (Blueprint $table) {
            $table->increments('id')->comment = "The make ID";
            $table->string('name')->comment = "Name make";
            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('autopart_list_models', function (Blueprint $table) {
            $table->increments('id')->comment = "The model ID";
            $table->string('name')->comment = "Name model";
            $table->integer('make_id')->unsigned();
            $table->foreign('make_id')->references('id')->on('autopart_list_makes');
            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('autopart_list_years', function (Blueprint $table) {
            $table->increments('id')->comment = "The year ID";
            $table->string('name')->comment = "Name year";
            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('autopart_list_origins', function (Blueprint $table) {
            $table->increments('id')->comment = "The origin ID";
            $table->string('name')->unique()->comment = "Name origin";
            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('autopart_list_status', function (Blueprint $table) {
            $table->increments('id')->comment = "The status ID";
            $table->string('name')->unique()->comment = "Name status";
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
        Schema::dropIfExists('autopart_list_makes');
        Schema::dropIfExists('autopart_list_models');
        Schema::dropIfExists('autopart_list_years');
        Schema::dropIfExists('autopart_list_origins');
        Schema::dropIfExists('autopart_list_status');
    }
}
