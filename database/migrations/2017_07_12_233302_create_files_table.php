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
            $table->increments('id')->comment = "The file ID";
            $table->text('name')->comment = "File name";
            $table->text('description')->nullable()->comment = "Long-form description";
            $table->text('basename')->nullable()->comment = "The disk name of file";
            $table->string('type')->nullable()->comment = "The type of file";
            $table->float('size')->nullable()->comment = "The size of file";
            $table->boolean('is_folder')->default(0)->comment = "Is folder or file";
            $table->boolean('favorite')->default(false)->comment = "Favorite file";
            $table->integer('parent_id')->default(0)->comment = "Parent of file";
            $table->integer('owner_id')->unsigned()->comment = "File owner";
            $table->foreign('owner_id')->references('id')->on('users');
            $table->unsignedInteger('created_by')->nullable()->default(null);
            $table->unsignedInteger('updated_by')->nullable()->default(null);
            $table->unsignedInteger('deleted_by')->nullable()->default(null);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('file_shares', function (Blueprint $table) {
            $table->integer('file_id')->comment = "File to shared";
            $table->integer('user_id')->comment = "User to shared file";
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
