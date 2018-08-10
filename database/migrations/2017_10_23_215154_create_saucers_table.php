<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaucersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saucers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('place')->nullable();
            $table->date('date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->text('content')->nullable();
            $table->float('price')->nullable();
            $table->integer('limit')->nullable();
            $table->boolean('enabled')->default(true);
            $table->integer('owner_id')->unsigned();
            $table->foreign('owner_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('saucer_images', function (Blueprint $table) {
            $table->increments('id');
            $table->text('basename')->nullable();
            $table->integer('order');
            $table->integer('saucer_id')->unsigned();
            $table->foreign('saucer_id')->references('id')->on('saucers');
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
        Schema::dropIfExists('saucer_images');
        Schema::dropIfExists('saucers');
    }
}
