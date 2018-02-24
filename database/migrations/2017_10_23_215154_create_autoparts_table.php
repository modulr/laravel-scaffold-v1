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

        Schema::create('autopart_year', function (Blueprint $table) {
            $table->integer('autopart_id')->unsigned()->comment = "Autopart to belongs";
            $table->foreign('autopart_id')->references('id')->on('autoparts');
            $table->integer('year_id')->unsigned()->comment = "Year to belongs";
            $table->foreign('year_id')->references('id')->on('autopart_list_years');
            $table->primary(['autopart_id', 'year_id']);
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
        Schema::dropIfExists('autoparts');
        Schema::dropIfExists('autopart_images');
        Schema::dropIfExists('autopart_year');
    }
}
