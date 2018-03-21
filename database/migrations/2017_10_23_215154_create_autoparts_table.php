<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAutopartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Lists
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

        // Autoparts
        Schema::create('autoparts', function (Blueprint $table) {
            $table->increments('id')->comment = "The Autopart ID";
            $table->string('name')->comment = "Autopart name";
            $table->text('description')->nullable()->comment = "Long-form description";

            $table->float('purchase_price')->nullable()->comment = "Autopart purchase price";
            $table->float('sale_price')->nullable()->comment = "Autopart sale price";

            $table->integer('make_id')->unsigned()->comment = "Make of Autopart";
            $table->foreign('make_id')->references('id')->on('autopart_list_makes');
            $table->integer('model_id')->unsigned()->comment = "Model of Autopart";
            $table->foreign('model_id')->references('id')->on('autopart_list_models');

            $table->integer('origin_id')->unsigned()->comment = "Origin of Autopart";
            $table->foreign('origin_id')->references('id')->on('autopart_list_origins');

            $table->integer('status_id')->unsigned()->comment = "Status of Autopart";
            $table->foreign('status_id')->references('id')->on('autopart_list_status');

            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('autopart_years', function (Blueprint $table) {
            $table->integer('autopart_id')->unsigned()->comment = "Autopart to belongs";
            $table->foreign('autopart_id')->references('id')->on('autoparts');
            $table->integer('year_id')->unsigned()->comment = "Year to belongs";
            $table->foreign('year_id')->references('id')->on('autopart_list_years');
            $table->primary(['autopart_id', 'year_id']);
        });

        Schema::create('autopart_images', function (Blueprint $table) {
            $table->increments('id')->comment = "The image ID";
            $table->text('basename')->nullable()->comment = "The disk name of image";
            $table->integer('order')->comment = "Order show the image";
            $table->integer('autopart_id')->unsigned()->comment = "Autopart to belongs";
            $table->foreign('autopart_id')->references('id')->on('autoparts');
            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('autopart_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->text('comment');
            $table->integer('autopart_id')->unsigned();
            $table->foreign('autopart_id')->references('id')->on('autoparts');
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
        //Schema::disableForeignKeyConstraints();
        // Autoparts
        Schema::dropIfExists('autopart_images');
        Schema::dropIfExists('autopart_comments');
        Schema::dropIfExists('autopart_years');
        Schema::dropIfExists('autoparts');
        // Lists
        Schema::dropIfExists('autopart_list_models');
        Schema::dropIfExists('autopart_list_makes');
        Schema::dropIfExists('autopart_list_years');
        Schema::dropIfExists('autopart_list_origins');
        Schema::dropIfExists('autopart_list_status');
        //Schema::enableForeignKeyConstraints();
    }
}
