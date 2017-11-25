<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id')->comment = "The event ID";
            $table->string('name')->comment = "Event name";
            $table->text('description')->nullable()->comment = "Long-form description";
            $table->text('place')->nullable()->comment = "Event Place information";
            $table->date('date')->nullable()->comment = "Event day";
            $table->time('start_time')->nullable()->comment = "Start time";
            $table->time('end_time')->nullable()->comment = "End time, if one has been set";
            $table->text('content')->nullable()->comment = "Detail of events";
            $table->float('price')->nullable()->comment = "Event cost";
            $table->integer('attending_limit')->nullable()->comment = "Attending limit";
            $table->integer('owner_id')->unsigned()->comment = "Event owner";
            $table->foreign('owner_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('event_images', function (Blueprint $table) {
            $table->increments('id')->comment = "The image ID";
            $table->text('basename')->nullable()->comment = "The disk name of image";
            $table->integer('order')->comment = "Order show the image";
            $table->integer('event_id')->unsigned()->comment = "Event to belongs";
            $table->foreign('event_id')->references('id')->on('events');
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
        Schema::dropIfExists('event_images');
        Schema::dropIfExists('events');
    }
}
