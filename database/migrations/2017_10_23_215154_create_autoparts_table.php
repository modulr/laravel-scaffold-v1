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

        // Autoparts
        Schema::create('autoparts', function (Blueprint $table) {
            $table->increments('id')->comment = "The Autopart ID";
            $table->string('name')->comment = "Autopart name";
            $table->text('description')->nullable()->comment = "Long-form description";
            $table->text('observations')->nullable()->comment = "observations";
            $table->float('purchase_price')->nullable()->comment = "Autopart purchase price";
            $table->float('sale_price')->nullable()->comment = "Autopart sale price";
            $table->text('buyer')->nullable()->comment = "Buyer of Autopart";
            $table->date('date_of_sale')->nullable()->comment = "Date of sale";
            $table->integer('status_id')->unsigned()->comment = "Status of Autopart";
            $table->foreign('status_id')->references('id')->on('autopart_status');
            $table->integer('make_id')->unsigned()->comment = "Make of Autopart";
            $table->foreign('make_id')->references('id')->on('autopart_list_makes');
            $table->integer('model_id')->unsigned()->comment = "Model of Autopart";
            $table->foreign('model_id')->references('id')->on('autopart_list_models');
            $table->integer('year_id')->unsigned()->comment = "Year of Autopart";
            $table->foreign('year_id')->references('id')->on('autopart_list_years');
            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });

        // images
        Schema::create('autopart_images', function (Blueprint $table) {
            $table->increments('id')->comment = "The image ID";
            $table->text('basename')->nullable()->comment = "The disk name of image";
            $table->integer('order')->comment = "Order show the image";
            $table->integer('autopart_id')->unsigned()->comment = "Autopart to belongs";
            $table->foreign('autopart_id')->references('id')->on('autoparts');
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
        Schema::dropIfExists('autopart_list_makes');
        Schema::dropIfExists('autopart_list_models');
        Schema::dropIfExists('autopart_list_years');
        Schema::dropIfExists('autopart_status');
        Schema::dropIfExists('autopart_images');
        Schema::dropIfExists('autoparts');
    }
}
