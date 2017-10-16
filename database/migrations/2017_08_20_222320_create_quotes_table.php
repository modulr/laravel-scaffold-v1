<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('quotes', function (Blueprint $table) {
          $table->increments('id');
          $table->string('name');
          $table->string('description')->nullable();
          $table->integer('owner_id')->unsigned();
          $table->foreign('owner_id')->references('id')->on('users');
          $table->integer('designer_id')->unsigned();
          $table->foreign('designer_id')->references('id')->on('employees');
          $table->integer('salesman_id')->unsigned();
          $table->foreign('salesman_id')->references('id')->on('employees');
          $table->integer('project_id')->unsigned();
          $table->foreign('project_id')->references('id')->on('projects');
          $table->integer('customer_id')->unsigned();
          $table->foreign('customer_id')->references('id')->on('customers');
          $table->integer('service_id')->unsigned();
          $table->foreign('service_id')->references('id')->on('list_type_services');
          $table->integer('currency_id')->unsigned();
          $table->foreign('currency_id')->references('id')->on('list_type_currencies');
          $table->date('request_date');
          $table->date('delivery_date')->nullable();
          $table->date('close_date')->nullable();
          $table->double('amount', 15, 8)->nullable();
          $table->integer('status_id');
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
        Schema::dropIfExists('quotes');
    }
}
