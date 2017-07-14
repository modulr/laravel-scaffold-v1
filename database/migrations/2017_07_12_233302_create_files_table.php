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
            $table->text('basename')->nullable();
            $table->string('extension')->nullable();
            $table->text('description')->nullable();
            $table->boolean('favorite')->default(false);
            $table->integer('parent_id')->default(0);
            $table->integer('type')->default(1); // 1.File, 2.Folder
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
