<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('owner_id')->unsigned();
            $table->foreign('owner_id')->references('id')->on('users');
            $table->integer('contact_id')->unsigned();
            $table->foreign('contact_id')->references('id')->on('clients');
            $table->integer('area_id')->unsigned();
            $table->foreign('area_id')->references('id')->on('list_areas');
            $table->datetime('start_date');
            $table->datetime('delivery_date')->nullable();
            $table->datetime('estimated_delivery_date')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status');
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
        Schema::dropIfExists('projects');
    }
}
