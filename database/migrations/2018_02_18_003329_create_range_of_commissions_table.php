<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRangeOfCommissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('range_of_commissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->double('lower_limit', 15, 8);
            $table->double('upper_limit', 15, 8);
            $table->double('percentage', 15, 8);
            $table->integer('type');
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
        Schema::dropIfExists('range_of_commissions');
    }
}
