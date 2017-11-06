<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
            $table->text('description')->nullable();
            $table->text('basename')->nullable();
            $table->string('type')->nullable();
            $table->float('size')->nullable();
            $table->boolean('is_folder')->default(0);
            $table->boolean('favorite')->default(false);
            $table->integer('parent_id')->default(0);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('file_shares', function (Blueprint $table) {
            $table->integer('file_id');
            $table->integer('user_id');
            $table->primary(['file_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_shares');
        Schema::dropIfExists('files');
    }
}
