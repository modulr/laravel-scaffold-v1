<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id')->comment = "The news ID";
            $table->text('name')->nullable()->comment = "News name";
            $table->text('video')->nullable()->comment = "Video url";
            $table->integer('type')->comment = "News type 1.Text, 2.Image, 3.Video";
            $table->json('likes');
            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();

        });

        Schema::create('news_images', function (Blueprint $table) {
            $table->increments('id')->comment = "The image ID";
            $table->text('basename')->nullable()->comment = "The disk name of image";
            $table->integer('news_id')->unsigned()->comment = "News to belongs";
            $table->foreign('news_id')->references('id')->on('news');
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
        Schema::dropIfExists('news_images');
        Schema::dropIfExists('news');
    }
}
