<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLeaderSupervisorToEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('projects', function (Blueprint $table) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            $table->dropForeign(['leader_id']);
            $table->dropForeign(['supervisor_id']);

            $table->foreign('leader_id')->references('id')->on('employees');
            $table->foreign('supervisor_id')->references('id')->on('employees');

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
